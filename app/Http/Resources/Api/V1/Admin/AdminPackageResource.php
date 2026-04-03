<?php

namespace App\Http\Resources\Api\V1\Admin;

use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminPackageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => ucfirst($this->type),
            'price' => $this->price,
            'billing_period' => $this->billing_period ?? Package::BILLING_1_MONTH,
            'billing_period_label' => $this->billingPeriodLabel(),
            'bookings_limit_label' => $this->bookingsLimitLabel(),
            'description' => $this->description,
            'max_staff' => $this->max_staff,
            'max_services' => $this->max_services,
            'max_bookings_per_month' => $this->max_bookings_per_month,
            'is_active' => $this->is_active,
            'created_at' => $this->formatDateTime($this->created_at),
        ];
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
