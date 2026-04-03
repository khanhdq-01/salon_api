<?php

namespace App\Http\Requests\Api\V1\Admin;

use App\Http\Requests\Api\V1\Customer\ListSalonRequest;
use App\Http\Requests\Concerns\ValidatesRouteUuids;

class ListOwnerSalonsRequest extends ListSalonRequest
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
        return ['ownerId'];
    }

    public function rules(): array
    {
        return array_merge(parent::rules(), $this->routeUuidRules());
    }
}
