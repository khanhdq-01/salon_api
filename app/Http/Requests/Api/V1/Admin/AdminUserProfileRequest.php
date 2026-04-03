<?php

namespace App\Http\Requests\Api\V1\Admin;

class AdminUserProfileRequest extends AdminAuthorizedRequest
{
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:200'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'avatar_url' => ['nullable', 'url', 'max:500'],
        ];
    }
}
