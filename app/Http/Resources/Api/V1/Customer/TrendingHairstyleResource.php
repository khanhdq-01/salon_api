<?php

namespace App\Http\Resources\Api\V1\Customer;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TrendingHairstyleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $salon = $this->service?->salon;

        return [
            'id' => $this->id,
            'salon_id' => $this->service?->salon_id,
            'service_id' => $this->service_id,
            'service_name' => $this->service?->name,
            'service_price' => (int) ($this->service?->price ?? 0),
            'salon_name' => $salon?->name,
            'salon_image_url' => $this->resolveSalonImageUrl($salon),
            'name' => $this->name,
            'gender' => $this->gender,
            'image' => $this->image,
            'image_url' => $this->image ? '/storage/' . str_replace('\\', '/', $this->image) : null,
            'extra_price' => (int) ($this->extra_price ?? 0),
            'bookings_count' => (int) ($this->bookings_count ?? 0),
        ];
    }

    private function resolveSalonImageUrl(mixed $salon): ?string
    {
        if (! $salon) {
            return null;
        }

        if ($salon->relationLoaded('images') && $salon->images->isNotEmpty()) {
            return $salon->images->first()->image_url;
        }

        return $salon->image_url ?: null;
    }
}
