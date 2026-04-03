<?php

namespace App\Http\Resources\Api\V1\Customer;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PopularServiceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'bookings_count' => (int) ($this->bookings_count ?? 0),
            'salon_count' => (int) ($this->salon_count ?? 0),
            'min_price' => (int) ($this->min_price ?? 0),
            'max_price' => (int) ($this->max_price ?? 0),
            'duration_minutes' => (int) ($this->min_duration ?? 0),
        ];
    }
}
