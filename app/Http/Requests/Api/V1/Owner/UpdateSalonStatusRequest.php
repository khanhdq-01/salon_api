<?php

namespace App\Http\Requests\Api\V1\Owner;

use App\Models\Salon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSalonStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => ['sometimes', Rule::in([Salon::STATUS_OPEN, Salon::STATUS_CLOSED])],
            'approval_status' => ['sometimes', Rule::in([
                Salon::APPROVAL_PENDING,
                Salon::APPROVAL_APPROVED,
                Salon::APPROVAL_REJECTED,
            ])],
            'is_locked' => ['sometimes', 'boolean'],
        ];
    }
}
