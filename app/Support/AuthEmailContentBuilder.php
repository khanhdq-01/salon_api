<?php

namespace App\Support;

use App\Contracts\Services\Admin\AdminSettingsServiceInterface;

class AuthEmailContentBuilder
{
    public function __construct(
        protected AdminSettingsServiceInterface $settingsService
    ) {}

    /**
     * @return array{
     *     system_name: string,
     *     logo_url: string|null,
     *     from_address: string,
     *     from_name: string
     * }
     */
    public function branding(): array
    {
        $settings = $this->settingsService->getSettings();

        return [
            'system_name' => $settings['system_name'] ?? config('app.name', 'Salonify SaaS'),
            'logo_url' => $settings['logo_url'] ?? null,
            'from_address' => $settings['email_sender_address'] ?? config('mail.from.address'),
            'from_name' => $settings['email_sender_name'] ?? config('mail.from.name'),
        ];
    }

    public function verificationUrl(string $plainToken, string $email): string
    {
        return rtrim((string) config('app.frontend_url'), '/')
            .'/verify-email?token='.urlencode($plainToken)
            .'&email='.urlencode($email);
    }

    public function resetPasswordUrl(string $plainToken, string $email): string
    {
        return rtrim((string) config('app.frontend_url'), '/')
            .'/reset-password?token='.urlencode($plainToken)
            .'&email='.urlencode($email);
    }
}
