<?php

namespace App\Http\Resources\Api\V1\Owner;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'salon_id' => $this->salon_id,
            'name' => $this->name,
            'price' => $this->price,
            'duration_minutes' => $this->duration_minutes,
            'is_active' => (bool) $this->is_active,
            'bookings_count' => (int) ($this->bookings_count ?? 0),
            'style_options' => $this->when(
                $this->relationLoaded('styleOptions'),
                fn () => ServiceStyleOptionResource::collection($this->styleOptions)
            ),
        ];
    }
}
