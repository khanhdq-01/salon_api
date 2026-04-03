<?php

namespace App\Support;

final class ServiceMapper
{
    public static function normalizeListFilters(array $data): array
    {
        return [
            'salon_id' => $data['salon_id'] ?? null,
            'query' => $data['q'] ?? null,
            'is_active' => array_key_exists('is_active', $data) ? (bool) $data['is_active'] : null,
            'public_only' => (bool) ($data['public_only'] ?? false),
            'page' => max(1, (int) ($data['page'] ?? 1)),
            'per_page' => min(100, max(1, (int) ($data['limit'] ?? $data['per_page'] ?? 15))),
        ];
    }

    public static function normalizeCreate(array $data): array
    {
        return [
            'salon_id' => $data['salon_id'],
            'name' => $data['name'],
            'price' => (int) $data['price'],
            'duration_minutes' => (int) $data['duration_minutes'],
            'is_active' => (bool) ($data['is_active'] ?? true),
            'bookings_count' => 0,
        ];
    }

    public static function normalizeUpdate(array $data): array
    {
        return array_filter([
            'name' => $data['name'] ?? null,
            'price' => isset($data['price']) ? (int) $data['price'] : null,
            'duration_minutes' => isset($data['duration_minutes']) ? (int) $data['duration_minutes'] : null,
            'is_active' => array_key_exists('is_active', $data) ? (bool) $data['is_active'] : null,
        ], fn ($value) => $value !== null);
    }
}
