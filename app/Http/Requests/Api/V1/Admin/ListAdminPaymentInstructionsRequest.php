<?php

namespace App\Http\Requests\Api\V1\Admin;

use App\Models\PaymentInstruction;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ListAdminPaymentInstructionsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'q' => ['nullable', 'string', 'max:200'],
            'status' => ['nullable', 'string', Rule::in([
                PaymentInstruction::STATUS_ACTIVE,
                PaymentInstruction::STATUS_INACTIVE,
                'Active',
                'Inactive',
            ])],
            'page' => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
            'limit' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }
}
