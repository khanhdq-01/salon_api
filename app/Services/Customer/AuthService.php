<?php

namespace App\Services\Customer;

use App\Contracts\Services\Admin\AdminSettingsServiceInterface;
use App\Contracts\Services\Customer\AuthServiceInterface;
use App\Contracts\Services\Customer\EmailVerificationServiceInterface;
use App\Exceptions\BusinessException;
use App\Jobs\SendResetPasswordJob;
use App\Models\User;
use App\Repositories\Interfaces\Customer\UserRepositoryInterface;
use App\Support\AuditLogger;
use App\Support\AuthEmailContentBuilder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class AuthService implements AuthServiceInterface
{
    public function __construct(
        protected UserRepositoryInterface $userRepository,
        protected EmailVerificationServiceInterface $emailVerificationService,
        protected AdminSettingsServiceInterface $settingsService,
        protected AuthEmailContentBuilder $emailContentBuilder,
    ) {}

    public function register(array $data): array
    {
        $data['id'] = Str::uuid();
        $data['role_id'] = \App\Models\Role::ID_CUSTOMER;
        $data['password'] = Hash::make($data['password']);
        $data['status'] = User::STATUS_PENDING;
        $data['email_verified_at'] = null;

        $user = $this->userRepository->create($data);
        $this->emailVerificationService->registerAndSendVerification($user);

        return [
            'user' => $user->load('role'),
        ];
    }

    public function login(array $credentials): string
    {
        if (! $token = Auth::attempt($credentials)) {
            throw new \Exception('Unauthorized');
        }

        /** @var User|null $user */
        $user = auth()->user();

        if ($user && $user->isCustomer() && ! $user->hasVerifiedEmail()) {
            Auth::logout();

            throw new BusinessException(
                'Email chưa được xác thực. Vui lòng kiểm tra hộp thư hoặc gửi lại email xác thực.',
                'EMAIL_NOT_VERIFIED',
                403
            );
        }

        return $token;
    }

    public function changePassword(User $user, string $password): void
    {
        $this->userRepository->updatePassword($user, $password);
    }

    public function resetPassword(string $email, string $token, string $password): void
    {
        $user = $this->userRepository->findByEmail($email);

        if (! $user || ! $user->isCustomer()) {
            throw new BusinessException('Token đặt lại mật khẩu không hợp lệ hoặc đã hết hạn.', 'RESET_INVALID', 422);
        }

        $status = Password::reset(
            [
                'email' => $email,
                'password' => $password,
                'password_confirmation' => $password,
                'token' => $token,
            ],
            function (User $user, string $password) {
                $this->userRepository->updatePassword($user, $password);
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            AuditLogger::log('reset_password', 'auth', $user->id, 'failed', [
                'target_label' => $email,
            ], $user->id);

            throw new BusinessException('Token đặt lại mật khẩu không hợp lệ hoặc đã hết hạn.', 'RESET_INVALID', 422);
        }

        AuditLogger::log('reset_password', 'auth', $user->id, 'success', [
            'target_label' => $email,
        ], $user->id);
    }

    public function forgotPassword(string $email): void
    {
        $user = $this->userRepository->findByEmail($email);

        if (! $user || ! $user->isCustomer() || $user->status === User::STATUS_SUSPENDED) {
            return;
        }

        $this->sendResetPasswordEmail($user);

        AuditLogger::log('forgot_password', 'auth', $user->id, 'success', [
            'target_label' => $user->email,
        ], $user->id);
    }

    public function logout(): void
    {
        Auth::logout();
    }

    public function logoutAllDevices(User $user): void
    {
        $user->token_version++;
        $user->save();
    }

    protected function sendResetPasswordEmail(User $user): bool
    {
        $settings = $this->settingsService->getSettings();

        if (! ($settings['enable_notifications'] ?? true)) {
            return false;
        }

        $resetUrl = null;

        try {
            $token = Password::createToken($user);
            $resetUrl = $this->emailContentBuilder->resetPasswordUrl($token, $user->email);
            $branding = $this->emailContentBuilder->branding();
            $customerName = e($user->name ?? 'Quý khách');
            $systemName = e($branding['system_name']);

            $introHtml = sprintf(
                '<p style="margin:0 0 12px;">Xin chào <strong>%s</strong>,</p>'.
                '<p style="margin:0 0 12px;">Chúng tôi nhận được yêu cầu đặt lại mật khẩu cho tài khoản của bạn.</p>'.
                '<p style="margin:0;">Nhấn nút bên dưới để tạo mật khẩu mới.</p>',
                $customerName
            );

            $bodyHtml = sprintf(
                '<p style="margin:0 0 8px;color:#64748b;font-size:13px;">Nếu nút không hoạt động, sao chép liên kết sau vào trình duyệt:</p>'.
                '<p style="margin:0;word-break:break-all;font-size:13px;"><a href="%s">%s</a></p>',
                e($resetUrl),
                e($resetUrl)
            );

            $footerHtml = sprintf(
                '<p style="margin:0 0 4px;">Nếu bạn không yêu cầu đặt lại mật khẩu, hãy bỏ qua email này.</p>'.
                '<p style="margin:0;">Trân trọng,<br><strong>%s</strong></p>',
                $systemName
            );

            SendResetPasswordJob::dispatch(
                recipientEmail: $user->email,
                subjectLine: 'Đặt lại mật khẩu',
                headline: 'Đặt lại mật khẩu',
                introHtml: $introHtml,
                footerHtml: $footerHtml,
                fromAddress: $branding['from_address'],
                fromName: $branding['from_name'],
                logoUrl: $branding['logo_url'] ?: null,
                systemName: $branding['system_name'],
                ctaUrl: $resetUrl,
                bodyHtml: $bodyHtml,
                expiresNotice: 'Liên kết đặt lại mật khẩu có hiệu lực trong 60 phút và chỉ sử dụng được một lần.',
            );

            return true;
        } catch (\Throwable $exception) {
            Log::warning('Failed to queue password reset email', [
                'user_id' => $user->id,
                'email' => $user->email,
                'error' => $exception->getMessage(),
            ]);

            if (app()->environment('local') && config('app.debug') && $resetUrl) {
                Log::info('Password reset link (local debug)', [
                    'email' => $user->email,
                    'reset_url' => $resetUrl,
                ]);
            }

            return false;
        }
    }
}
