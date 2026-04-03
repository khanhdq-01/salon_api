<?php

namespace Database\Seeders\Support;

final class DemoPrimarySalon
{
    public const SALON_INDEX = 0;

    public const OWNER_EMAIL = 'owner@gmail.com';

    public const ADMIN_EMAIL = DemoSeederConstants::ADMIN_EMAIL;

    public const PASSWORD = DemoSeederConstants::PASSWORD;

    public const GENERATED_BOOKING_NOTE_PREFIX = '[[demo-gen]]';

    public const TARGET_BOOKING_COUNT = 100;

    /** @var list<string> */
    public const STAFF_ACCOUNTS = [
        'staff1@gmail.com',
        'staff2@gmail.com',
        'staff3@gmail.com',
        'staff4@gmail.com',
        'staff5@gmail.com',
        'staff6@gmail.com',
    ];

    /** @var list<array{start_time: string, end_time: string, label: string}> */
    public const STAFF_SHIFTS = [
        ['start_time' => '08:00:00', 'end_time' => '17:00:00', 'label' => 'Ca sáng A'],
        ['start_time' => '09:00:00', 'end_time' => '18:00:00', 'label' => 'Ca sáng B'],
        ['start_time' => '10:00:00', 'end_time' => '19:00:00', 'label' => 'Ca trưa C'],
        ['start_time' => '08:30:00', 'end_time' => '17:30:00', 'label' => 'Ca sáng D'],
        ['start_time' => '08:00:00', 'end_time' => '12:00:00', 'label' => 'Ca nửa ngày'],
        ['start_time' => '13:00:00', 'end_time' => '20:00:00', 'label' => 'Ca chiều'],
    ];

    /** @var list<string> */
    public const CUSTOMER_NOTES = [
        'VIP Customer',
        'First Visit',
        'Allergic to hair dye',
        'Birthday Promotion',
        'Khách quen, thích stylist cẩn thận',
        'Đặt combo cắt + gội',
        'Khách muốn slot sáng sớm',
        'Ưu tiên ghế gần cửa sổ',
        null,
        null,
    ];

    /** @var list<int> */
    public const BOOKING_DURATIONS = [30, 45, 60, 90, 120];
}
