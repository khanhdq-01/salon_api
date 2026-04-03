<?php

namespace App\Http\Requests\Api\V1\Customer;

use Illuminate\Foundation\Http\FormRequest;

class VerifyEmailRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'email' => is_string($this->email) ? trim($this->email) : $this->email,
            'token' => is_string($this->token) ? trim($this->token) : $this->token,
        ]);
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'max:150'],
            'token' => ['required', 'string', 'min:32', 'max:255'],
        ];
    }
}
