<?php

namespace App\Http\Requests\Api\V1\Admin;

use App\Http\Requests\Concerns\ValidatesRouteUuids;

class AdminChangeStaffSalonRequest extends AdminAuthorizedRequest
{
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
            'salon_id' => ['required', 'uuid', 'exists:salons,id'],
        ]);
    }
}
