<?php

namespace App\Http\Resources\Api\V1\Customer;

use App\Contracts\Services\Owner\OwnerSalonSettingsServiceInterface;
use App\Http\Resources\Api\V1\Owner\ServiceResource;
use App\Http\Resources\Api\V1\Owner\StaffResource;
use App\Http\Resources\Api\V1\Customer\BookingServiceLineResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'salon_id' => $this->salon_id,
            'customer_id' => $this->customer_id,
            'staff_id' => $this->staff_id,
            'seat_id' => $this->seat_id,
            'booking_date' => $this->formatDate($this->booking_date),
            'booking_time' => substr((string) $this->booking_time, 0, 5),
            'status' => $this->status,
            'total_price' => $this->total_price,
            'total_duration_minutes' => $this->total_duration_minutes,
            'customer_notes' => $this->customer_notes,
            'cancel_reason' => $this->cancel_reason,
            'has_reviewed' => $this->has_reviewed,
            'salon' => $this->whenLoaded('salon', fn () => [
                'id' => $this->salon->id,
                'name' => $this->salon->name,
                'address' => $this->salon->address,
            ]),
            'customer' => $this->whenLoaded('customer', fn () => [
                'id' => $this->customer->id,
                'name' => $this->customer->name,
                'phone' => $this->customer->phone,
            ]),
            'staff' => $this->whenLoaded('staff', fn () => new StaffResource($this->staff)),
            'seat' => $this->whenLoaded('seat', fn () => [
                'id' => $this->seat->id,
                'name' => $this->seat->name,
            ]),
            'services' => $this->resolveServiceLines($request),
            'can_cancel' => $this->resolveCanCancel(),
            'cancel_deadline_at' => $this->resolveCancelDeadlineAt(),
            'created_at' => $this->formatDateTime($this->created_at),
        ];
    }

    private function resolveServiceLines(Request $request): mixed
    {
        if ($this->relationLoaded('bookingServices')) {
            return BookingServiceLineResource::collection($this->bookingServices);
        }

        if ($this->relationLoaded('services')) {
            return ServiceResource::collection($this->services);
        }

        return [];
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

    private function resolveCanCancel(): bool
    {
        if (! $this->salon_id) {
            return false;
        }

        $settings = app(OwnerSalonSettingsServiceInterface::class)->getForSalon($this->salon_id);

        return $settings->customerCanCancelBooking($this->resource);
    }

    private function resolveCancelDeadlineAt(): ?string
    {
        if (! $this->salon_id) {
            return null;
        }

        if (! in_array($this->status, [\App\Models\Booking::STATUS_PENDING, \App\Models\Booking::STATUS_CONFIRMED], true)) {
            return null;
        }

        $settings = app(OwnerSalonSettingsServiceInterface::class)->getForSalon($this->salon_id);

        return $settings->customerCancelDeadline($this->resource)->toIso8601String();
    }

    private function formatDateTime(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof \DateTimeInterface) {
            return $value->format(DATE_ATOM);
        }

        return (string) $value;
    }
}
