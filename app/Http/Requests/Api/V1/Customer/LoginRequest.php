<?php

namespace App\Http\Requests\Api\V1\Customer;

use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LoginRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email' => 'required|email',
            'password' => 'required|string',
            'portal' => ['nullable', Rule::in([Role::CUSTOMER, Role::OWNER, Role::ADMIN, Role::STAFF])],
        ];
    }
}