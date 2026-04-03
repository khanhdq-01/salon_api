<?php

namespace App\Http\Resources\Api\V1\Owner;

use App\Http\Resources\Api\V1\Owner\ServiceResource;
use App\Support\TimeFormat;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StaffResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'salon_id' => $this->salon_id,
            'name' => $this->name,
            'avatar_url' => $this->avatar_url,
            'bio' => $this->bio,
            'experience_years' => $this->experience_years,
            'is_active' => $this->is_active,
            'services' => ServiceResource::collection($this->whenLoaded('services')),
            'schedules' => $this->whenLoaded('schedules', fn () => $this->schedules->map(fn ($schedule) => [
                'id' => $schedule->id,
                'work_date' => $this->formatDate($schedule->work_date),
                'start_time' => substr((string) $schedule->start_time, 0, 5),
                'end_time' => substr((string) $schedule->end_time, 0, 5),
                'status' => $schedule->status,
                'submitted_by' => $schedule->submitted_by,
                'note' => $schedule->note,
                'approved_at' => TimeFormat::toIso8601($schedule->approved_at),
            ])),
            'login' => $this->whenLoaded('user', fn () => [
                'email' => $this->user?->email,
                'phone' => $this->user?->phone,
                'has_account' => (bool) $this->user,
            ]),
        ];
    }

    private function formatDate(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof \DateTimeInterface) {
            return $value->format('Y-m-d');
        }

        return substr((string) $value, 0, 10);
    }
}
