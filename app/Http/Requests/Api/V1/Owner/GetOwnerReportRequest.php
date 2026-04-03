<?php

namespace App\Http\Requests\Api\V1\Owner;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GetOwnerReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isOwner() ?? false;
    }

    public function rules(): array
    {
        $periods = ['day', 'week', 'month', 'quarter', 'year'];
        $today = now()->toDateString();

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
            'chart_period' => ['nullable', Rule::in($periods)],
            'chart_date' => ['nullable', 'date', 'before_or_equal:'.$today],
            'summary_period' => ['nullable', Rule::in($periods)],
            'summary_date' => ['nullable', 'date', 'before_or_equal:'.$today],
            'services_period' => ['nullable', Rule::in($periods)],
            'services_date' => ['nullable', 'date', 'before_or_equal:'.$today],
            'staff_period' => ['nullable', Rule::in($periods)],
            'staff_date' => ['nullable', 'date', 'before_or_equal:'.$today],
            'top_period' => ['nullable', Rule::in($periods)],
            'top_date' => ['nullable', 'date', 'before_or_equal:'.$today],
        ];
    }
}
