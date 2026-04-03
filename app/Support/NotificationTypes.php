<?php

namespace App\Support;

class NotificationTypes
{
    public const GENERAL = 'general';

    public const PROMO = 'promo';

    public const COMBO = 'combo';

    public const OPENING = 'opening';

    public const HIRING = 'hiring';

    /**
     * @return list<string>
     */
    public static function values(): array
    {
        return [
            self::GENERAL,
            self::PROMO,
            self::COMBO,
            self::OPENING,
            self::HIRING,
        ];
    }

    public static function isValid(?string $type): bool
    {
        return $type !== null && in_array($type, self::values(), true);
    }

    public static function label(string $type): string
    {
        return match ($type) {
            self::PROMO => 'Khuyến mãi',
            self::COMBO => 'Combo',
            self::OPENING => 'Khai trương',
            self::HIRING => 'Tuyển dụng',
            default => 'Thông báo',
        };
    }
}
