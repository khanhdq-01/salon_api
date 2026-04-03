<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    /** @var list<array{name: string, type: string, price: int, billing_period: string, description: string, max_staff: int, max_services: int, max_bookings_per_month: int}> */
    private array $definitions = [
        [
            'name' => 'Dùng thử',
            'type' => 'basic',
            'price' => 0,
            'billing_period' => Package::BILLING_1_MONTH,
            'description' => 'Gói dùng thử miễn phí 1 tháng',
            'max_staff' => 2,
            'max_services' => 5,
            'max_bookings_per_month' => 50,
        ],
        [
            'name' => 'Cơ bản',
            'type' => 'basic',
            'price' => 200_000,
            'billing_period' => Package::BILLING_1_MONTH,
            'description' => 'Gói cơ bản cho salon mới',
            'max_staff' => 5,
            'max_services' => 10,
            'max_bookings_per_month' => 100,
        ],
        [
            'name' => 'Tiêu chuẩn',
            'type' => 'premium',
            'price' => 500_000,
            'billing_period' => Package::BILLING_3_MONTHS,
            'description' => 'Gói tiêu chuẩn cho salon đang phát triển',
            'max_staff' => 10,
            'max_services' => 20,
            'max_bookings_per_month' => 300,
        ],
        [
            'name' => 'Cao cấp',
            'type' => 'premium',
            'price' => 1_000_000,
            'billing_period' => Package::BILLING_3_MONTHS,
            'description' => 'Gói cao cấp với nhiều nhân sự và dịch vụ',
            'max_staff' => 20,
            'max_services' => 30,
            'max_bookings_per_month' => 500,
        ],
        [
            'name' => 'Doanh nghiệp',
            'type' => 'enterprise',
            'price' => 2_000_000,
            'billing_period' => Package::BILLING_1_YEAR,
            'description' => 'Gói doanh nghiệp cho chuỗi salon',
            'max_staff' => 50,
            'max_services' => 50,
            'max_bookings_per_month' => 2000,
        ],
    ];

    public function run(): void
    {
        foreach ($this->definitions as $definition) {
            Package::query()->create(array_merge($definition, ['is_active' => true]));
        }
    }
}
