<?php

namespace App\Http\Requests\Shared;

use App\Http\Requests\Concerns\UuidRouteRequest;

class RouteOwnerIdRequest extends UuidRouteRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    /** @return list<string> */
    protected function routeUuidParameters(): array
    {
        return ['ownerId'];
    }
}
