<?php

namespace App\Support;

final class SalonMapper
{
    public static function normalizeListFilters(array $data): array
    {
        return [
            'query' => self::normalizeQuery($data['q'] ?? $data['query'] ?? null),
            'rating_min' => isset($data['rating_min']) ? (float) $data['rating_min'] : null,
            'status' => $data['status'] ?? null,
            'approval_status' => $data['approval_status'] ?? null,
            'owner_id' => $data['owner_id'] ?? null,
            'lat' => isset($data['lat']) ? (float) $data['lat'] : null,
            'lng' => isset($data['lng']) ? (float) $data['lng'] : null,
            'distance_km' => isset($data['distance_km'])
                ? (float) $data['distance_km']
                : (isset($data['distance']) ? (float) $data['distance'] : null),
            'available_today' => filter_var($data['available_today'] ?? false, FILTER_VALIDATE_BOOLEAN),
            'available_salon_ids' => $data['available_salon_ids'] ?? null,
            'public_only' => (bool) ($data['public_only'] ?? true),
            'with_trashed' => (bool) ($data['with_trashed'] ?? false),
            'sort' => $data['sort'] ?? null,
            'page' => max(1, (int) ($data['page'] ?? 1)),
            'per_page' => min(100, max(1, (int) ($data['limit'] ?? $data['per_page'] ?? 15))),
        ];
    }

    public static function normalizeCreate(array $data): array
    {
        return [
            'owner_id' => $data['owner_id'],
            'name' => $data['name'],
            'address' => $data['address'],
            'phone' => $data['phone'] ?? null,
            'description' => HtmlSanitizer::richHtml($data['description'] ?? null),
            'lat' => isset($data['lat']) ? (float) $data['lat'] : null,
            'lng' => isset($data['lng']) ? (float) $data['lng'] : null,
            'image_url' => $data['image_url'] ?? null,
            'open_time' => TimeFormat::normalize($data['open_time'] ?? '09:00'),
            'close_time' => TimeFormat::normalize($data['close_time'] ?? '20:00'),
            'status' => $data['status'] ?? 'open',
            'approval_status' => $data['approval_status'] ?? 'pending',
        ];
    }

    public static function normalizeUpdate(array $data): array
    {
        $payload = array_filter([
            'name' => $data['name'] ?? null,
            'address' => $data['address'] ?? null,
            'phone' => $data['phone'] ?? null,
            'description' => HtmlSanitizer::richHtml($data['description'] ?? null),
            'lat' => array_key_exists('lat', $data) ? (float) $data['lat'] : null,
            'lng' => array_key_exists('lng', $data) ? (float) $data['lng'] : null,
            'image_url' => $data['image_url'] ?? null,
            'status' => $data['status'] ?? null,
        ], fn ($value) => $value !== null);

        if (isset($data['open_time'])) {
            $payload['open_time'] = TimeFormat::normalize($data['open_time']);
        }

        if (isset($data['close_time'])) {
            $payload['close_time'] = TimeFormat::normalize($data['close_time']);
        }

        return $payload;
    }

    public static function normalizeStatusUpdate(array $data): array
    {
        return array_filter([
            'status' => $data['status'] ?? null,
            'approval_status' => $data['approval_status'] ?? null,
            'is_locked' => array_key_exists('is_locked', $data) ? (bool) $data['is_locked'] : null,
        ], fn ($value) => $value !== null);
    }

    private static function normalizeQuery(?string $query): ?string
    {
        if ($query === null) {
            return null;
        }

        $trimmed = trim($query);

        return $trimmed === '' ? null : $trimmed;
    }
}
