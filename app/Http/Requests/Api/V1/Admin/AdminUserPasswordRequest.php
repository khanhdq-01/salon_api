<?php

namespace App\Http\Requests\Api\V1\Admin;

use App\Http\Requests\Concerns\ValidatesPasswords;

class AdminUserPasswordRequest extends AdminAuthorizedRequest
{
    use ValidatesPasswords;

    public function rules(): array
    {
        return [
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
