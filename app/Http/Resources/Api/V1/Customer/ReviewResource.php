<?php

namespace App\Http\Resources\Api\V1\Customer;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $viewerId = $request->user()?->id;
        $isOwner = $viewerId !== null && $viewerId === $this->customer_id;

        return [
            'id' => $this->id,
            'salon_id' => $this->salon_id,
            'customer_id' => $this->customer_id,
            'rating' => $this->rating,
            'comment' => $this->comment,
            'customer' => $this->whenLoaded('customer', fn () => [
                'id' => $this->customer->id,
                'name' => $this->customer->name,
                'avatar_url' => $this->customer->avatar_url,
            ]),
            'can_edit' => $isOwner,
            'can_delete' => $isOwner,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
