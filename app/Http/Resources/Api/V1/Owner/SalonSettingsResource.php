<?php

namespace App\Http\Resources\Api\V1\Owner;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SalonSettingsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'salon_id' => $this->salon_id,
            'auto_confirm_booking' => (bool) $this->auto_confirm_booking,
            'customer_cancel_before_minutes' => (int) $this->customer_cancel_before_minutes,
            'booking_interval_minutes' => (int) $this->booking_interval_minutes,
            'auto_approve_work_schedule' => (bool) $this->auto_approve_work_schedule,
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
