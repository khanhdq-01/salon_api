<?php

namespace App\Http\Requests\Api\V1\Customer;

use App\Http\Requests\Concerns\ValidatesPasswords;
use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
{
    use ValidatesPasswords;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'new_password' => $this->requiredPasswordRule(),
        ];
    }

    public function messages(): array
    {
        return [
            'new_password.required' => 'Vui lòng nhập mật khẩu mới.',
        ];
    }
}
