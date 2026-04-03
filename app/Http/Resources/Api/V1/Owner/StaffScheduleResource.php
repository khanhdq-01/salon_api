<?php

namespace App\Http\Resources\Api\V1\Owner;

use App\Support\TimeFormat;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StaffScheduleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'staff_id' => $this->staff_id,
            'staff_name' => $this->whenLoaded('staff', fn () => $this->staff?->name),
            'work_date' => $this->formatDate($this->work_date),
            'start_time' => substr((string) $this->start_time, 0, 5),
            'end_time' => substr((string) $this->end_time, 0, 5),
            'status' => $this->status,
            'submitted_by' => $this->submitted_by,
            'note' => $this->note,
            'approved_by' => $this->approved_by,
            'approved_by_name' => $this->whenLoaded('approver', fn () => $this->approver?->name),
            'approved_at' => TimeFormat::toIso8601($this->approved_at),
            'created_at' => TimeFormat::toIso8601($this->created_at),
            'updated_at' => TimeFormat::toIso8601($this->updated_at),
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
