<?php

namespace App\Support;

final class StaffMapper
{
    public static function normalizeListFilters(array $data): array
    {
        return [
            'salon_id' => $data['salon_id'] ?? null,
            'is_active' => array_key_exists('is_active', $data) ? (bool) $data['is_active'] : null,
            'page' => max(1, (int) ($data['page'] ?? 1)),
            'per_page' => min(100, max(1, (int) ($data['limit'] ?? $data['per_page'] ?? 15))),
        ];
    }

    public static function normalizeCreate(array $data): array
    {
        return [
            'salon_id' => $data['salon_id'],
            'name' => $data['name'],
            'avatar_url' => $data['avatar_url'] ?? null,
            'bio' => $data['bio'] ?? null,
            'experience_years' => isset($data['experience_years']) ? (int) $data['experience_years'] : null,
            'is_active' => (bool) ($data['is_active'] ?? true),
        ];
    }

    public static function normalizeUpdate(array $data): array
    {
        return array_filter([
            'name' => $data['name'] ?? null,
            'avatar_url' => array_key_exists('avatar_url', $data) ? $data['avatar_url'] : null,
            'bio' => array_key_exists('bio', $data) ? $data['bio'] : null,
            'experience_years' => isset($data['experience_years']) ? (int) $data['experience_years'] : null,
            'is_active' => isset($data['is_active']) ? (bool) $data['is_active'] : null,
        ], fn ($value) => $value !== null);
    }
}
