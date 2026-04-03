<?php

namespace App\Http\Resources\Api\V1\Owner;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** Compact staff row for owner list — no full schedule payload. */
class StaffListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'salon_id' => $this->salon_id,
            'name' => $this->name,
            'avatar_url' => $this->avatar_url,
            'is_active' => $this->is_active,
            'services' => ServiceResource::collection($this->whenLoaded('services')),
            'weekly_shifts_count' => (int) ($this->weekly_approved_shifts_count ?? 0),
            'today_has_shift' => (bool) ($this->today_approved_shifts_count ?? 0),
            'pending_requests_count' => (int) ($this->pending_schedules_count ?? 0),
            'login' => $this->whenLoaded('user', fn () => [
                'email' => $this->user?->email,
                'phone' => $this->user?->phone,
                'has_account' => (bool) $this->user,
            ]),
        ];
    }
}
