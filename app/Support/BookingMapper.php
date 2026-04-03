<?php

namespace App\Support;

final class BookingMapper
{
    public static function normalizeListFilters(array $data): array
    {
        $allowedPerPage = [10, 20, 50];
        $perPage = (int) ($data['limit'] ?? $data['per_page'] ?? 10);
        if (! in_array($perPage, $allowedPerPage, true)) {
            $perPage = 10;
        }

        return [
            'salon_id' => $data['salon_id'] ?? null,
            'customer_id' => $data['customer_id'] ?? null,
            'staff_id' => $data['staff_id'] ?? null,
            'status' => $data['status'] ?? null,
            'date_from' => $data['date_from'] ?? $data['start_date'] ?? null,
            'date_to' => $data['date_to'] ?? $data['end_date'] ?? null,
            'date' => $data['date'] ?? null,
            'sort' => $data['sort'] ?? null,
            'page' => max(1, (int) ($data['page'] ?? 1)),
            'per_page' => $perPage,
        ];
    }

    public static function normalizeCreate(array $data): array
    {
        return [
            'salon_id' => $data['salon_id'],
            'service_ids' => $data['service_ids'],
            'style_options' => $data['style_options'] ?? [],
            'booking_date' => $data['date'] ?? $data['booking_date'],
            'booking_time' => TimeFormat::normalize($data['time'] ?? $data['booking_time']),
            'staff_id' => $data['staff_id'],
            'seat_id' => $data['seat_id'] ?? null,
            'customer_notes' => $data['customer_notes'] ?? null,
        ];
    }

    public static function normalizeCancel(array $data): array
    {
        return [
            'cancel_reason' => $data['cancel_reason'] ?? $data['reason'] ?? null,
        ];
    }

    public static function normalizeReschedule(array $data): array
    {
        return [
            'booking_date' => $data['date'] ?? $data['booking_date'],
            'booking_time' => TimeFormat::normalize($data['time'] ?? $data['booking_time']),
            'staff_id' => $data['staff_id'] ?? null,
            'seat_id' => $data['seat_id'] ?? null,
        ];
    }

    public static function normalizeAvailableSlotFilters(array $data): array
    {
        return [
            'date' => isset($data['date']) ? substr((string) $data['date'], 0, 10) : null,
            'service_ids' => array_values($data['service_ids'] ?? []),
            'style_options' => is_array($data['style_options'] ?? null) ? $data['style_options'] : [],
        ];
    }
}
