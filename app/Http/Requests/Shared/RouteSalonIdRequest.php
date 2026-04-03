<?php

namespace App\Http\Requests\Shared;

use App\Http\Requests\Concerns\UuidRouteRequest;

class RouteSalonIdRequest extends UuidRouteRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return list<string> */
    protected function routeUuidParameters(): array
    {
        return ['salonId'];
    }
}
