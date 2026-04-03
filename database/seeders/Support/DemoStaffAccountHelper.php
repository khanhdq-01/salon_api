<?php

namespace Database\Seeders\Support;

final class DemoStaffAccountHelper
{
    /**
     * @return array{email: string, phone: string, last_login_days_ago: int}
     */
    public static function accountFor(int $salonIndex, int $memberIndex, string $name): array
    {
        if ($salonIndex === DemoPrimarySalon::SALON_INDEX && $memberIndex < count(DemoPrimarySalon::STAFF_ACCOUNTS)) {
            return [
                'email' => DemoPrimarySalon::STAFF_ACCOUNTS[$memberIndex],
                'phone' => '0909'.str_pad((string) ($memberIndex + 1), 6, '0', STR_PAD_LEFT),
                'last_login_days_ago' => $memberIndex,
            ];
        }

        $slug = self::nameSlug($name);

        return [
            'email' => "{$slug}.salon".($salonIndex + 1).'.nv'.($memberIndex + 1).'@gmail.com',
            'phone' => '0918'.str_pad((string) (($salonIndex * 10) + $memberIndex + 1), 6, '0', STR_PAD_LEFT),
            'last_login_days_ago' => ($salonIndex + $memberIndex) % 14,
        ];
    }

    private static function nameSlug(string $name): string
    {
        $normalized = mb_strtolower(trim($name), 'UTF-8');
        $ascii = strtr($normalized, [
            'à' => 'a', 'á' => 'a', 'ả' => 'a', 'ã' => 'a', 'ạ' => 'a',
            'ă' => 'a', 'ằ' => 'a', 'ắ' => 'a', 'ẳ' => 'a', 'ẵ' => 'a', 'ặ' => 'a',
            'â' => 'a', 'ầ' => 'a', 'ấ' => 'a', 'ẩ' => 'a', 'ẫ' => 'a', 'ậ' => 'a',
            'è' => 'e', 'é' => 'e', 'ẻ' => 'e', 'ẽ' => 'e', 'ẹ' => 'e',
            'ê' => 'e', 'ề' => 'e', 'ế' => 'e', 'ể' => 'e', 'ễ' => 'e', 'ệ' => 'e',
            'ì' => 'i', 'í' => 'i', 'ỉ' => 'i', 'ĩ' => 'i', 'ị' => 'i',
            'ò' => 'o', 'ó' => 'o', 'ỏ' => 'o', 'õ' => 'o', 'ọ' => 'o',
            'ô' => 'o', 'ồ' => 'o', 'ố' => 'o', 'ổ' => 'o', 'ỗ' => 'o', 'ộ' => 'o',
            'ơ' => 'o', 'ờ' => 'o', 'ớ' => 'o', 'ở' => 'o', 'ỡ' => 'o', 'ợ' => 'o',
            'ù' => 'u', 'ú' => 'u', 'ủ' => 'u', 'ũ' => 'u', 'ụ' => 'u',
            'ư' => 'u', 'ừ' => 'u', 'ứ' => 'u', 'ử' => 'u', 'ữ' => 'u', 'ự' => 'u',
            'ỳ' => 'y', 'ý' => 'y', 'ỷ' => 'y', 'ỹ' => 'y', 'ỵ' => 'y',
            'đ' => 'd',
        ]);

        $slug = preg_replace('/[^a-z0-9]+/', '', $ascii) ?? '';

        return $slug !== '' ? $slug : 'nhanvien';
    }
}
