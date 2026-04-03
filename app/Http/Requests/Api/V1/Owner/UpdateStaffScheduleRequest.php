<?php

namespace App\Http\Requests\Api\V1\Owner;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStaffScheduleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'schedules' => ['required', 'array', 'min:1'],
            'schedules.*.work_date' => ['required_without:schedules.*.date', 'date'],
            'schedules.*.date' => ['required_without:schedules.*.work_date', 'date'],
            'schedules.*.start_time' => ['nullable', 'date_format:H:i'],
            'schedules.*.start' => ['nullable', 'date_format:H:i'],
            'schedules.*.end_time' => ['nullable', 'date_format:H:i'],
            'schedules.*.end' => ['nullable', 'date_format:H:i'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            foreach ($this->input('schedules', []) as $index => $schedule) {
                $start = $schedule['start_time'] ?? $schedule['start'] ?? null;
                $end = $schedule['end_time'] ?? $schedule['end'] ?? null;
                $date = $schedule['work_date'] ?? $schedule['date'] ?? null;

                if (! $date) {
                    $validator->errors()->add("schedules.{$index}.work_date", 'Ngày làm việc là bắt buộc.');
                }

                if (! $start) {
                    $validator->errors()->add("schedules.{$index}.start_time", 'Giờ bắt đầu là bắt buộc.');
                }

                if (! $end) {
                    $validator->errors()->add("schedules.{$index}.end_time", 'Giờ kết thúc là bắt buộc.');
                }

                if ($start && $end && $start >= $end) {
                    $validator->errors()->add("schedules.{$index}.end_time", 'Giờ kết thúc phải sau giờ bắt đầu.');
                }
            }
        });
    }
}
