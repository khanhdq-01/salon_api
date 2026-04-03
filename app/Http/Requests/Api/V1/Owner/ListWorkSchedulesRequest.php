<?php

namespace App\Http\Requests\Api\V1\Owner;

use App\Models\StaffSchedule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ListWorkSchedulesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isOwner() ?? false;
    }

    public function rules(): array
    {
        return [
            'staff_id' => ['nullable', 'uuid', 'exists:staff,id'],
            'status' => ['nullable', Rule::in(StaffSchedule::STATUSES)],
            'submitted_by' => ['nullable', Rule::in([StaffSchedule::SUBMITTED_BY_OWNER, StaffSchedule::SUBMITTED_BY_STAFF])],
            'date' => ['nullable', 'date'],
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', Rule::when(filled($this->input('from')), ['after_or_equal:from'])],
            'start_date' => ['nullable', 'date'],
            'end_date' => [
                'nullable',
                'date',
                Rule::when(
                    filled($this->input('start_date')),
                    ['after_or_equal:start_date']
                ),
            ],
            'view' => ['nullable', Rule::in(['day', 'week', 'month'])],
            'period' => ['nullable', Rule::in(['day', 'week', 'month'])],
            'page' => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', 'integer', Rule::in([10, 20, 50])],
        ];
    }
}
