<?php

namespace App\Services\Customer;

use App\Contracts\Services\Admin\AdminSettingsServiceInterface;
use App\Mail\SubscriptionExpiryNotificationMail;
use App\Models\User;
use App\Support\QueuedMailer;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;

class PasswordResetEmailService
{
    public function __construct(
        protected AdminSettingsServiceInterface $settingsService
    ) {}

    public function sendResetLink(User $user): bool
    {
        $settings = $this->settingsService->getSettings();

        if (! ($settings['enable_notifications'] ?? true)) {
            return false;
        }

        $resetUrl = null;

        try {
            $token = Password::createToken($user);
            $resetUrl = rtrim((string) config('app.frontend_url'), '/')
                .'/reset-password?token='.urlencode($token)
                .'&email='.urlencode($user->email);

            $this->dispatchEmail($user, $resetUrl, $settings);

            return true;
        } catch (\Throwable $exception) {
            Log::warning('Failed to send password reset email', [
                'user_id' => $user->id,
                'email' => $user->email,
                'error' => $exception->getMessage(),
            ]);

            if (app()->environment('local') && config('app.debug') && $resetUrl) {
                Log::info('Password reset link (mail failed — copy for local testing)', [
                    'email' => $user->email,
                    'reset_url' => $resetUrl,
                ]);
            }

            return false;
        }
    }

    protected function dispatchEmail(User $user, string $resetUrl, array $settings): void
    {
        $customerName = $user->name ?? 'Quý khách';
        $systemName = $settings['system_name'] ?? config('app.name', 'Salonify SaaS');
        $subject = 'Đặt lại mật khẩu';

        $headerHtml = sprintf(
            '<p style="margin: 0 0 12px;">Xin chào <strong>%s</strong>,</p>'.
            '<p style="margin: 0 0 12px;">Chúng tôi nhận được yêu cầu đặt lại mật khẩu cho tài khoản của bạn.</p>'.
            '<p style="margin: 0 0 12px;">Nhấn nút bên dưới để tạo mật khẩu mới. Liên kết có hiệu lực trong <strong>60 phút</strong>.</p>'.
            '<p style="margin: 0 0 16px;">'.
            '<a href="%s" style="display:inline-block;padding:12px 24px;background:#6366f1;color:#ffffff;text-decoration:none;border-radius:8px;font-weight:600;">Đặt lại mật khẩu</a>'.
            '</p>'.
            '<p style="margin: 0 0 4px;color:#6b7280;font-size:13px;">Nếu bạn không yêu cầu đặt lại mật khẩu, hãy bỏ qua email này.</p>',
            e($customerName),
            e($resetUrl)
        ).'<hr style="border: none; border-top: 1px solid #e5e7eb; margin: 16px 0;">';

        $bodyHtml = sprintf(
            '<p style="margin: 0 0 8px;color:#6b7280;font-size:13px;">Nếu nút không hoạt động, sao chép liên kết sau vào trình duyệt:</p>'.
            '<p style="margin: 0;word-break:break-all;font-size:13px;"><a href="%s">%s</a></p>',
            e($resetUrl),
            e($resetUrl)
        );

        $footerHtml = sprintf(
            '<p style="margin: 0 0 4px;">Trân trọng,</p><p style="margin: 0;"><strong>%s</strong></p>',
            e($systemName)
        );

        $fromAddress = $settings['email_sender_address'] ?? config('mail.from.address');
        $fromName = $settings['email_sender_name'] ?? config('mail.from.name');

        $mail = new SubscriptionExpiryNotificationMail(
            subjectLine: $subject,
            headerHtml: $headerHtml,
            bodyHtml: $bodyHtml,
            footerHtml: $footerHtml,
            fromAddress: $fromAddress,
            fromName: $fromName,
        );

        QueuedMailer::to($user->email, $mail);
    }
}
