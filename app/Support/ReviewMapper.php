<?php

namespace App\Support;

use App\Models\ReviewReport;

final class ReviewMapper
{
    public static function normalizeListFilters(array $data): array
    {
        return [
            'rating_min' => isset($data['rating_min']) ? (int) $data['rating_min'] : null,
            'page' => max(1, (int) ($data['page'] ?? 1)),
            'per_page' => min(100, max(1, (int) ($data['limit'] ?? $data['per_page'] ?? 15))),
        ];
    }

    public static function normalizeCreate(array $data): array
    {
        return [
            'rating' => (int) $data['rating'],
            'comment' => HtmlSanitizer::plainText(trim($data['comment'] ?? '')) ?? '',
        ];
    }

    public static function normalizeUpdate(array $data): array
    {
        return array_filter([
            'rating' => isset($data['rating']) ? (int) $data['rating'] : null,
            'comment' => array_key_exists('comment', $data)
                ? (HtmlSanitizer::plainText(trim((string) $data['comment'])) ?? '')
                : null,
        ], fn ($value) => $value !== null);
    }

    public static function normalizeReport(array $data): array
    {
        $reason = HtmlSanitizer::plainText(trim($data['reason'] ?? '')) ?? '';

        if (! empty($data['details'] ?? '')) {
            $details = HtmlSanitizer::plainText(trim($data['details'])) ?? '';
            $reason .= ($reason !== '' && $details !== '' ? ' — ' : '').$details;
        }

        return [
            'reason' => $reason,
            'status' => ReviewReport::STATUS_PENDING,
        ];
    }
}
