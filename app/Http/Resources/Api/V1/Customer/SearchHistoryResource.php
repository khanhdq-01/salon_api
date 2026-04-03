<?php

namespace App\Http\Resources\Api\V1\Customer;

use App\Support\SalonImageResolver;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SearchHistoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $salon = $this->salon;

        return [
            'id' => $this->id,
            'type' => $this->type ?? 'query',
            'query' => $this->query,
            'salon_id' => $this->salon_id,
            'salon_name' => $salon?->name,
            'salon_image' => $salon ? SalonImageResolver::resolve($salon) : null,
            'salon_address' => $salon?->address,
            'salon_rating' => $salon?->rating_avg,
            'searched_at' => $this->searched_at?->toIso8601String(),
        ];
    }
}
