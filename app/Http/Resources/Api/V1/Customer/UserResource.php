<?php

namespace App\Http\Resources\Api\V1\Customer;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'avatar_url' => $this->avatar_url,
            'role' => $this->whenLoaded('role', fn () => $this->role->name),
            'status' => $this->status,
            'email_verified_at' => $this->email_verified_at?->toIso8601String(),
            'is_email_verified' => $this->hasVerifiedEmail(),
            'language' => $this->language ?? 'vi',
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
