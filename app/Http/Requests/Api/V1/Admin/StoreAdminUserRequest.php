<?php

namespace App\Http\Requests\Api\V1\Admin;

use App\Http\Requests\Concerns\ValidatesPasswords;
use App\Models\Role;
use App\Models\User;
use Illuminate\Validation\Rule;

class StoreAdminUserRequest extends AdminAuthorizedRequest
{
    use ValidatesPasswords;

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:200'],
            'email' => ['required', 'email', 'max:200', 'unique:users,email'],
            'password' => $this->requiredPasswordRule(),
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'role' => ['required', Rule::in([Role::CUSTOMER, Role::OWNER])],
            'status' => ['nullable', Rule::in([
                User::STATUS_ACTIVE,
                User::STATUS_PENDING,
                User::STATUS_SUSPENDED,
            ])],
        ];
    }

    public function messages(): array
    {
        return [
            'password.required' => 'Vui lòng nhập mật khẩu khi tạo tài khoản.',
        ];
    }
}
