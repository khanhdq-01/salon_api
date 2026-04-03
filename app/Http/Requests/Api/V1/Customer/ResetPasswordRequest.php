<?php

namespace App\Http\Requests\Api\V1\Customer;

use App\Http\Requests\Concerns\ValidatesPasswords;
use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
{
    use ValidatesPasswords;

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
            'token' => ['required', 'string'],
            'password' => $this->requiredPasswordRule(true),
        ];
    }

    public function messages(): array
    {
        return [
            'password.required' => 'Vui lòng nhập mật khẩu mới.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
        ];
    }
}
