<?php

namespace App\Services\Customer;

use App\Contracts\Services\Admin\AdminSettingsServiceInterface;
use App\Contracts\Services\Customer\EmailVerificationServiceInterface;
use App\Exceptions\BusinessException;
use App\Jobs\SendVerifyEmailJob;
use App\Models\User;
use App\Repositories\Interfaces\Customer\EmailVerificationTokenRepositoryInterface;
use App\Repositories\Interfaces\Customer\UserRepositoryInterface;
use App\Support\AuditLogger;
use App\Support\AuthEmailContentBuilder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class EmailVerificationService implements EmailVerificationServiceInterface
{
    public const TOKEN_TTL_MINUTES = 60;

    public function __construct(
        protected UserRepositoryInterface $userRepository,
        protected EmailVerificationTokenRepositoryInterface $tokenRepository,
        protected AdminSettingsServiceInterface $settingsService,
        protected AuthEmailContentBuilder $emailContentBuilder,
    ) {}

    public function registerAndSendVerification(User $user): void
    {
        $this->dispatchVerificationEmail($user, 'register');
    }

    public function verify(string $email, string $plainToken): User
    {
        $user = $this->userRepository->findByEmail(trim($email));

        if (! $user || ! $user->isCustomer()) {
            AuditLogger::log('verify_email', 'auth', null, 'failed', [
                'target_label' => $email,
                'reason' => 'user_not_found',
            ]);

            throw new BusinessException('Liên kết xác thực không hợp lệ hoặc đã hết hạn.', 'VERIFICATION_INVALID', 422);
        }

        if ($user->hasVerifiedEmail()) {
            return $user->load('role');
        }

        $token = $this->tokenRepository->findValidForUser($user, $plainToken);

        if (! $token) {
            $latest = $this->tokenRepository->findLatestActiveForUser($user);

            AuditLogger::log('verify_email', 'auth', $user->id, 'failed', [
                'target_label' => $user->email,
                'reason' => $latest && $latest->isExpired() ? 'expired' : 'invalid',
            ], $user->id);

            if ($latest && $latest->isExpired()) {
                throw new BusinessException('Liên kết đã hết hạn.', 'VERIFICATION_EXPIRED', 410);
            }

            throw new BusinessException('Liên kết xác thực không hợp lệ hoặc đã hết hạn.', 'VERIFICATION_INVALID', 422);
        }

        $this->tokenRepository->markConsumed($token);
        $this->tokenRepository->invalidateActiveTokensForUser($user);
        $user = $this->userRepository->markEmailVerified($user);

        AuditLogger::log('verify_email', 'auth', $user->id, 'success', [
            'target_label' => $user->email,
        ], $user->id);

        return $user->load('role');
    }

    public function resend(string $email): void
    {
        $user = $this->userRepository->findByEmail(trim($email));

        if (! $user || ! $user->isCustomer() || $user->hasVerifiedEmail()) {
            return;
        }

        $this->dispatchVerificationEmail($user, 'resend');
    }

    protected function dispatchVerificationEmail(User $user, string $source): void
    {
        $settings = $this->settingsService->getSettings();

        if (! ($settings['enable_notifications'] ?? true)) {
            return;
        }

        $plainToken = Str::random(64);
        $this->tokenRepository->invalidateActiveTokensForUser($user);
        $this->tokenRepository->createForUser(
            $user,
            Hash::make($plainToken),
            now()->addMinutes(self::TOKEN_TTL_MINUTES)
        );

        $branding = $this->emailContentBuilder->branding();
        $verifyUrl = $this->emailContentBuilder->verificationUrl($plainToken, $user->email);
        $customerName = e($user->name ?? 'Quý khách');
        $systemName = e($branding['system_name']);

        $introHtml = sprintf(
            '<p style="margin:0 0 12px;">Xin chào <strong>%s</strong>,</p>'.
            '<p style="margin:0 0 12px;">Cảm ơn bạn đã đăng ký tài khoản tại <strong>%s</strong>.</p>'.
            '<p style="margin:0;">Vui lòng xác thực email để kích hoạt tài khoản và bắt đầu sử dụng dịch vụ.</p>',
            $customerName,
            $systemName
        );

        $bodyHtml = sprintf(
            '<p style="margin:0 0 8px;color:#64748b;font-size:13px;">Nếu nút không hoạt động, sao chép liên kết sau vào trình duyệt:</p>'.
            '<p style="margin:0;word-break:break-all;font-size:13px;"><a href="%s">%s</a></p>',
            e($verifyUrl),
            e($verifyUrl)
        );

        $footerHtml = sprintf(
            '<p style="margin:0 0 4px;">Nếu bạn không tạo tài khoản, hãy bỏ qua email này.</p>'.
            '<p style="margin:0;">Trân trọng,<br><strong>%s</strong></p>',
            $systemName
        );

        SendVerifyEmailJob::dispatch(
            recipientEmail: $user->email,
            subjectLine: 'Xác thực Email đăng ký tài khoản',
            headline: 'Xác thực Email',
            introHtml: $introHtml,
            footerHtml: $footerHtml,
            fromAddress: $branding['from_address'],
            fromName: $branding['from_name'],
            logoUrl: $branding['logo_url'] ?: null,
            systemName: $branding['system_name'],
            ctaUrl: $verifyUrl,
            bodyHtml: $bodyHtml,
            expiresNotice: 'Liên kết xác thực có hiệu lực trong 60 phút và chỉ sử dụng được một lần.',
        );

        AuditLogger::log(
            $source === 'resend' ? 'resend_verification_email' : 'send_verification_email',
            'auth',
            $user->id,
            'success',
            ['target_label' => $user->email],
            $user->id
        );

        if (app()->environment('local') && config('app.debug')) {
            Log::info('Email verification link (local debug)', [
                'email' => $user->email,
                'verify_url' => $verifyUrl,
            ]);
        }
    }
}
