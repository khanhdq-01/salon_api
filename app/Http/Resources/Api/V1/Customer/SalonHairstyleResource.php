<?php

namespace App\Http\Resources\Api\V1\Customer;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SalonHairstyleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'salon_id' => $this->service?->salon_id,
            'service_id' => $this->service_id,
            'service_name' => $this->service?->name,
            'name' => $this->name,
            'gender' => $this->gender ?? 'unisex',
            'description' => $this->description,
            'article' => $this->article,
            'extra_price' => $this->extra_price,
            'extra_duration' => $this->extra_duration,
            'image' => $this->image,
            'image_url' => $this->image ? '/storage/' . str_replace('\\', '/', $this->image) : null,
            'sort_order' => $this->sort_order,
        ];
    }
}
