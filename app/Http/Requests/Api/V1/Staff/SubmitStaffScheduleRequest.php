<?php

namespace App\Http\Requests\Api\V1\Staff;

use Illuminate\Foundation\Http\FormRequest;

class SubmitStaffScheduleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isStaff() ?? false;
    }

    protected function prepareForValidation(): void
    {
        $schedules = $this->input('schedules', []);

        if (! is_array($schedules)) {
            return;
        }

        $normalized = array_map(function ($item) {
            if (! is_array($item)) {
                return $item;
            }

            foreach (['start_time', 'start', 'end_time', 'end'] as $field) {
                if (! empty($item[$field])) {
                    $item[$field] = substr((string) $item[$field], 0, 5);
                }
            }

            return $item;
        }, $schedules);

        $this->merge(['schedules' => $normalized]);
    }

    public function rules(): array
    {
        return [
            'schedules' => ['required', 'array', 'min:1'],
            'schedules.*.work_date' => ['required_without:schedules.*.date', 'date'],
            'schedules.*.date' => ['nullable', 'date'],
            'schedules.*.start_time' => ['required_without:schedules.*.start', 'date_format:H:i'],
            'schedules.*.start' => ['nullable', 'date_format:H:i'],
            'schedules.*.end_time' => ['required_without:schedules.*.end', 'date_format:H:i'],
            'schedules.*.end' => ['nullable', 'date_format:H:i'],
        ];
    }
}
