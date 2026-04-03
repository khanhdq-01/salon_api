<?php

namespace App\Services\Admin;

use App\Contracts\Services\Admin\AdminSettingsServiceInterface;
use App\Repositories\Interfaces\Admin\SystemSettingRepositoryInterface;
use App\Support\AuditLogger;
use App\Support\HtmlSanitizer;
use App\Support\SystemSettings;
use Illuminate\Support\Facades\Auth;

class AdminSettingsService implements AdminSettingsServiceInterface
{
    private const SETTINGS_KEY = 'app';

    public function __construct(
        protected SystemSettingRepositoryInterface $systemSettingRepository
    ) {}

    public function getSettings(): array
    {
        $record = $this->systemSettingRepository->findByKey(self::SETTINGS_KEY);

        if (! $record) {
            return $this->defaults();
        }

        $settings = array_merge($this->defaults(), $record->value ?? []);

        if (! isset($settings['language']) && isset($settings['default_language'])) {
            $settings['language'] = $settings['default_language'];
        }

        return $settings;
    }

    public function updateSettings(array $data): array
    {
        $payload = array_merge($this->getSettings(), array_intersect_key($data, array_flip(array_keys($this->defaults()))));

        foreach (['system_name', 'app_description', 'support_email', 'support_phone', 'email_sender_name', 'email_sender_address'] as $textField) {
            if (array_key_exists($textField, $payload)) {
                $payload[$textField] = HtmlSanitizer::plainText($payload[$textField]) ?? '';
            }
        }

        if (isset($payload['language'])) {
            $payload['default_language'] = $payload['language'];
        }

        $this->systemSettingRepository->saveByKey(self::SETTINGS_KEY, $payload, Auth::id());
        SystemSettings::invalidateCache();

        AuditLogger::log('Updated system settings', 'settings', self::SETTINGS_KEY, 'success');

        return $payload;
    }

    protected function defaults(): array
    {
        return [
            'system_name' => 'Salonify SaaS',
            'logo_url' => '',
            'support_email' => 'support@salonify.vn',
            'support_phone' => '',
            'timezone' => 'Asia/Ho_Chi_Minh',
            'language' => 'vi',
            'currency' => 'VND',
            'email_sender_name' => 'Salonify Admin',
            'email_sender_address' => 'admin@salonify.vn',
            'enable_notifications' => true,
            'app_qr_url' => '',
            'app_image_url' => '',
            'app_image_url_2' => '',
            'app_description' => '',
        ];
    }
}
