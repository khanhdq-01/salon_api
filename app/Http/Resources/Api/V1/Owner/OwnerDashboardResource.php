<?php

namespace App\Http\Resources\Api\V1\Owner;

use App\Http\Resources\Api\V1\Customer\BookingResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OwnerDashboardResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $data = is_array($this->resource) ? $this->resource : [];

        return [
            'stats' => $data['stats'] ?? [],
            'upcoming_bookings' => collect($data['upcoming_bookings'] ?? [])
                ->map(fn ($booking) => (new BookingResource($booking))->resolve())
                ->values()
                ->all(),
            'upcoming_meta' => $data['upcoming_meta'] ?? [
                'current_page' => 1,
                'last_page' => 1,
                'per_page' => 10,
                'total' => 0,
            ],
            'chart_bookings' => $data['chart_bookings'] ?? ['labels' => [], 'values' => []],
            'chart_revenue' => $data['chart_revenue'] ?? ['labels' => [], 'values' => []],
            'chart_staff' => $data['chart_staff'] ?? ['available' => 0, 'busy' => 0],
        ];
    }
}
