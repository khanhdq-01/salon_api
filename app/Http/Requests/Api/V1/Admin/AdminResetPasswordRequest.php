<?php

namespace App\Http\Requests\Api\V1\Admin;

use App\Http\Requests\Concerns\ValidatesPasswords;
use App\Http\Requests\Concerns\ValidatesRouteUuids;

class AdminResetPasswordRequest extends AdminAuthorizedRequest
{
    use ValidatesPasswords;
    use ValidatesRouteUuids;

    protected function prepareForValidation(): void
    {
        $this->prepareRouteUuidValidation();
    }

    /** @return list<string> */
    protected function routeUuidParameters(): array
    {
        return ['id'];
    }

    public function rules(): array
    {
        return array_merge($this->routeUuidRules(), [
            'password' => $this->requiredPasswordRule(true),
        ]);
    }

    public function messages(): array
    {
        return [
            'password.required' => 'Vui lòng nhập mật khẩu mới khi reset.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
        ];
    }
}
