<?php

namespace Database\Seeders\Support;

final class DemoServiceCatalog
{
    /** @var list<array{key: string, name: string, duration_minutes: int, has_styles: bool}> */
    public const SERVICES = [
        ['key' => 'cut_male', 'name' => DemoSeederConstants::SERVICE_CUT_MALE, 'duration_minutes' => 30, 'has_styles' => true],
        ['key' => 'cut_female', 'name' => DemoSeederConstants::SERVICE_CUT_FEMALE, 'duration_minutes' => 45, 'has_styles' => true],
        ['key' => 'wash', 'name' => 'Gội đầu', 'duration_minutes' => 30, 'has_styles' => false],
        ['key' => 'massage', 'name' => 'Massage đầu', 'duration_minutes' => 40, 'has_styles' => false],
        ['key' => 'shave', 'name' => 'Cạo râu', 'duration_minutes' => 20, 'has_styles' => false],
        ['key' => 'dye', 'name' => 'Nhuộm tóc', 'duration_minutes' => 150, 'has_styles' => false],
        ['key' => 'perm', 'name' => 'Uốn tóc', 'duration_minutes' => 120, 'has_styles' => false],
        ['key' => 'straighten', 'name' => 'Duỗi tóc', 'duration_minutes' => 120, 'has_styles' => false],
        ['key' => 'styling', 'name' => 'Tạo kiểu', 'duration_minutes' => 60, 'has_styles' => false],
        ['key' => 'recovery', 'name' => 'Phục hồi tóc', 'duration_minutes' => 90, 'has_styles' => false],
    ];

    /** @var array<string, array{min: int, max: int}> */
    public const PRICE_RANGES = [
        'cut_male' => ['min' => 70_000, 'max' => 150_000],
        'cut_female' => ['min' => 120_000, 'max' => 250_000],
        'wash' => ['min' => 40_000, 'max' => 120_000],
        'massage' => ['min' => 100_000, 'max' => 250_000],
        'shave' => ['min' => 30_000, 'max' => 70_000],
        'dye' => ['min' => 500_000, 'max' => 1_500_000],
        'perm' => ['min' => 600_000, 'max' => 1_800_000],
        'straighten' => ['min' => 500_000, 'max' => 1_500_000],
        'styling' => ['min' => 80_000, 'max' => 200_000],
        'recovery' => ['min' => 200_000, 'max' => 600_000],
    ];

    /** @var list<float> */
    private const SALON_PRICE_FACTORS = [
        0.82, 0.84, 0.86, 0.88, 0.90, 0.92, 0.94, 0.96, 0.98, 1.00,
        1.02, 1.04, 1.06, 1.08, 1.10, 1.12, 1.14, 1.16, 1.18, 1.20,
        0.85, 0.91, 0.97, 1.03, 1.09, 1.15, 0.89, 0.95, 1.01, 1.07,
    ];

    public static function priceFactor(int $salonIndex): float
    {
        return self::SALON_PRICE_FACTORS[$salonIndex % count(self::SALON_PRICE_FACTORS)];
    }

    public static function priceForSalon(int $salonIndex, string $serviceKey): int
    {
        $range = self::PRICE_RANGES[$serviceKey];
        $midpoint = ($range['min'] + $range['max']) / 2;

        return (int) round($midpoint * self::priceFactor($salonIndex));
    }
}
