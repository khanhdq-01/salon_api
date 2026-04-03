<?php

namespace App\Http\Requests\Api\V1\Admin;

use App\Http\Requests\Api\V1\Owner\UpdateStaffRequest;
use App\Http\Requests\Concerns\ValidatesRouteUuids;

class UpdateAdminStaffRequest extends UpdateStaffRequest
{
    use ValidatesRouteUuids;

    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

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
        return array_merge($this->routeUuidRules(), parent::rules());
    }
}
