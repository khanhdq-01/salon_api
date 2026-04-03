<?php

namespace App\Http\Requests\Api\V1\Admin;

use App\Models\Role;
use Illuminate\Validation\Rule;

class AdminUserRoleRequest extends AdminAuthorizedRequest
{
    public function rules(): array
    {
        return [
            'role' => ['required', Rule::in([Role::CUSTOMER, Role::OWNER])],
        ];
    }
}
