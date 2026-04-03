<?php

namespace App\Http\Requests\Api\V1\Owner;

use App\Http\Requests\Concerns\CastsQueryBooleans;
use Illuminate\Foundation\Http\FormRequest;

class ListStaffRequest extends FormRequest
{
    use CastsQueryBooleans;

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->castQueryBooleans(['is_active']);
    }

    public function rules(): array
    {
        return [
            'salon_id' => ['nullable', 'uuid', 'exists:salons,id'],
            'is_active' => ['nullable', 'boolean'],
            'page' => ['nullable', 'integer', 'min:1'],
            'limit' => ['nullable', 'integer', 'min:1', 'max:100'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }
}
