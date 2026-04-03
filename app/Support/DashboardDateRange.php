<?php

namespace App\Support;

use Carbon\Carbon;
use Illuminate\Support\Collection;

final class DashboardDateRange
{
    public static function normalize(array $filters): array
    {
        if (! empty($filters['start_date']) && ! empty($filters['end_date'])) {
            $start = Carbon::parse($filters['start_date'])->startOfDay();
            $end = Carbon::parse($filters['end_date'])->endOfDay();
        } else {
            $start = Carbon::now()->startOfMonth()->startOfDay();
            $end = Carbon::now()->endOfMonth()->endOfDay();
        }

        $allowedPerPage = [10, 20, 50];
        $perPage = (int) ($filters['per_page'] ?? 10);
        if (! in_array($perPage, $allowedPerPage, true)) {
            $perPage = 10;
        }

        return [
            'start' => $start,
            'end' => $end,
            'start_date' => $start->toDateString(),
            'end_date' => $end->toDateString(),
            'page' => max(1, (int) ($filters['page'] ?? 1)),
            'per_page' => $perPage,
        ];
    }

    public static function paginateItems(array $items, int $page, int $perPage): array
    {
        $total = count($items);
        $lastPage = max(1, (int) ceil($total / $perPage));
        $page = min($page, $lastPage);
        $offset = ($page - 1) * $perPage;

        return [
            'items' => array_values(array_slice($items, $offset, $perPage)),
            'meta' => [
                'current_page' => $page,
                'last_page' => $lastPage,
                'per_page' => $perPage,
                'total' => $total,
            ],
        ];
    }

    public static function paginateCollection(Collection $items, int $page, int $perPage): array
    {
        return self::paginateItems($items->values()->all(), $page, $perPage);
    }
}
