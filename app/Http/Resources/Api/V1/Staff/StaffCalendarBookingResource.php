<?php

namespace App\Http\Resources\Api\V1\Staff;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StaffCalendarBookingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $startTime = substr((string) $this->booking_time, 0, 5);
        $duration = (int) $this->total_duration_minutes;
        [$hour, $minute] = array_map('intval', explode(':', $startTime));
        $endMinutes = ($hour * 60) + $minute + $duration;

        return [
            'id' => $this->id,
            'customer' => $this->customer?->name ?? $this->walk_in_customer_name,
            'customer_phone' => $this->customer?->phone,
            'service' => $this->bookingServices
                ->map(fn ($line) => $line->service?->name)
                ->filter()
                ->implode(', ') ?: null,
            'date' => $this->formatDate($this->booking_date),
            'start_time' => $startTime,
            'end_time' => sprintf('%02d:%02d', intdiv($endMinutes, 60), $endMinutes % 60),
            'status' => $this->status,
            'total_price' => (int) $this->total_price,
            'seat' => $this->seat?->name,
        ];
    }

    private function formatDate(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof \DateTimeInterface) {
            return $value->format('Y-m-d');
        }

        return substr((string) $value, 0, 10);
    }
}
