<?php

namespace App\Http\Requests\Api\V1\Owner;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GetOwnerDashboardRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isOwner() ?? false;
    }

    public function rules(): array
    {
        return [
            'start_date' => ['nullable', 'date'],
            'end_date' => [
                'nullable',
                'date',
                Rule::when(
                    filled($this->input('start_date')),
                    ['after_or_equal:start_date']
                ),
            ],
            'page' => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', 'integer', Rule::in([10, 20, 50])],
            'upcoming_per_page' => ['nullable', 'integer', Rule::in([10, 20, 50])],
            'upcoming_page' => ['nullable', 'integer', 'min:1'],
        ];
    }
}
