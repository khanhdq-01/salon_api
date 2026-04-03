<?php

namespace App\Http\Requests\Api\V1\Admin;

use App\Http\Requests\Concerns\CastsQueryBooleans;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ListAdminUsersRequest extends FormRequest
{
    use CastsQueryBooleans;

    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    protected function prepareForValidation(): void
    {
        $this->castQueryBooleans(['is_locked']);
    }

    public function rules(): array
    {
        return [
            'q' => ['nullable', 'string', 'max:200'],
            'role_id' => ['nullable', 'integer', 'exists:roles,id'],
            'role' => ['nullable', Rule::in([Role::CUSTOMER, Role::OWNER, Role::ADMIN, Role::STAFF])],
            'status' => ['nullable', Rule::in([
                User::STATUS_ACTIVE,
                User::STATUS_PENDING,
                User::STATUS_SUSPENDED,
            ])],
            'is_locked' => ['nullable', 'boolean'],
            'page' => ['nullable', 'integer', 'min:1'],
            'limit' => ['nullable', 'integer', 'min:1', 'max:100'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }
}
