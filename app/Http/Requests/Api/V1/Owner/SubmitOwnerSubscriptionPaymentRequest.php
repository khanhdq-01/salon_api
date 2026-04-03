<?php

namespace App\Http\Requests\Api\V1\Owner;

use App\Http\Requests\Concerns\ValidatesStorageImagePaths;
use Illuminate\Foundation\Http\FormRequest;

class SubmitOwnerSubscriptionPaymentRequest extends FormRequest
{
    use ValidatesStorageImagePaths;
    public function authorize(): bool
    {
        return $this->user()?->isOwner() ?? false;
    }

    public function rules(): array
    {
        return [
            'payment_proof' => $this->storageImagePathRule(['subscription-payments']),
            'payment_note' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
