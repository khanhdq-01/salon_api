<?php

namespace App\Support;

use App\Contracts\Services\Admin\AdminSettingsServiceInterface;
use Illuminate\Support\Facades\Cache;

/**
 * Cached read helper for system-wide settings.
 * Use this outside admin context to avoid circular dependencies.
 */
class SystemSettings
{
    private const CACHE_KEY = 'system:settings';
    private const CACHE_TTL = 3600; // 1 hour

    private static ?array $cachedSettings = null;

    /**
     * Get a system setting by key.
     * Defaults to null if not found.
     *
     * @param  string  $key
     * @param  mixed  $default
     * @return mixed
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $settings = self::all();

        return $settings[$key] ?? $default;
    }

    /**
     * Check if a boolean setting is enabled.
     *
     * @param  string  $key
     * @return bool
     */
    public static function isEnabled(string $key): bool
    {
        return (bool) self::get($key, false);
    }

    /**
     * Get all system settings (cached).
     *
     * @return array
     */
    public static function all(): array
    {
        if (self::$cachedSettings !== null) {
            return self::$cachedSettings;
        }

        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            try {
                $service = app(AdminSettingsServiceInterface::class);

                return self::$cachedSettings = $service->getSettings();
            } catch (\Exception $e) {
                // Fallback to defaults if service unavailable
                return self::$cachedSettings = self::defaults();
            }
        });
    }

    /**
     * Invalidate cache (called after admin settings update).
     *
     * @return void
     */
    public static function invalidateCache(): void
    {
        self::$cachedSettings = null;
        Cache::forget(self::CACHE_KEY);
    }

    /**
     * Default values (matches AdminSettingsService::defaults()).
     *
     * @return array
     */
    private static function defaults(): array
    {
        return [
            'system_name' => 'Salonify SaaS',
            'logo_url' => '',
            'support_email' => 'support@salonify.vn',
            'support_phone' => '',
            'default_timezone' => 'Asia/Ho_Chi_Minh',
            'default_language' => 'vi',
            'language' => 'vi',
            'timezone' => 'Asia/Ho_Chi_Minh',
            'currency' => 'VND',
            'email_sender_name' => 'Salonify Admin',
            'email_sender_address' => 'admin@salonify.vn',
            'enable_notifications' => true,
            'allow_salon_registration' => true,
            'require_admin_approval' => true,
            'allow_free_trial' => true,
            'free_trial_days' => 7,
            'subscription_grace_period_days' => 3,
            'auto_lock_salon_on_expiry' => false,
            'app_qr_url' => '',
            'app_image_url' => '',
            'app_image_url_2' => '',
            'app_description' => '',
        ];
    }
}
