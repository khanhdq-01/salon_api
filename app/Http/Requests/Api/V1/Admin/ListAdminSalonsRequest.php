<?php

namespace App\Http\Requests\Api\V1\Admin;

use App\Models\Salon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ListAdminSalonsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'q' => ['nullable', 'string', 'max:200'],
            'approval_status' => ['nullable', Rule::in([
                Salon::APPROVAL_PENDING,
                Salon::APPROVAL_APPROVED,
                Salon::APPROVAL_REJECTED,
            ])],
            'status' => ['nullable', Rule::in([Salon::STATUS_OPEN, Salon::STATUS_CLOSED])],
            'page' => ['nullable', 'integer', 'min:1'],
            'limit' => ['nullable', 'integer', 'min:1', 'max:100'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
            'with_trashed' => ['nullable', 'boolean'],
        ];
    }
}
