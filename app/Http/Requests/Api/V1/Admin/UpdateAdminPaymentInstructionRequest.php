<?php

namespace App\Http\Requests\Api\V1\Admin;

use App\Http\Requests\Concerns\ValidatesStorageImagePaths;
use App\Models\PaymentInstruction;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAdminPaymentInstructionRequest extends FormRequest
{
    use ValidatesStorageImagePaths;
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'required', 'string', 'max:200'],
            'bank_name' => ['nullable', 'string', 'max:150'],
            'account_number' => ['nullable', 'string', 'max:100'],
            'account_holder' => ['nullable', 'string', 'max:150'],
            'transfer_content' => ['nullable', 'string', 'max:255'],
            'qr_code_image' => $this->storageImagePathRule(['style-options']),
            'content' => ['nullable', 'string'],
            'status' => ['nullable', 'string', Rule::in([
                PaymentInstruction::STATUS_ACTIVE,
                PaymentInstruction::STATUS_INACTIVE,
                'Active',
                'Inactive',
            ])],
        ];
    }
}
