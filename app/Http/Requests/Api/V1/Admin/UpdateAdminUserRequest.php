<?php

namespace App\Http\Requests\Api\V1\Admin;

use App\Models\User;
use Illuminate\Validation\Rule;

class UpdateAdminUserRequest extends AdminAuthorizedRequest
{
    public function rules(): array
    {
        $userId = $this->route('id');

        return [
            'name' => ['sometimes', 'string', 'max:200'],
            'email' => ['sometimes', 'email', 'max:200', Rule::unique('users', 'email')->ignore($userId)],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'status' => ['sometimes', Rule::in([
                User::STATUS_ACTIVE,
                User::STATUS_PENDING,
                User::STATUS_SUSPENDED,
            ])],
        ];
    }
}
