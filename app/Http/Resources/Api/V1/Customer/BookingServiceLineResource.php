<?php

namespace App\Http\Resources\Api\V1\Customer;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingServiceLineResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $styleOption = $this->relationLoaded('styleOption') ? $this->styleOption : null;

        return [
            'line_id' => $this->id,
            'id' => $this->service_id,
            'service_id' => $this->service_id,
            'name' => $this->whenLoaded('service', fn () => $this->service?->name),
            'price' => $this->price,
            'duration_minutes' => $this->duration_minutes,
            'style_option_id' => $this->service_style_option_id,
            'style_option_name' => $styleOption?->name,
            'style_option' => $styleOption ? [
                'id' => $styleOption->id,
                'name' => $styleOption->name,
                'extra_price' => $styleOption->extra_price,
                'extra_duration' => $styleOption->extra_duration,
            ] : null,
        ];
    }
}
