<?php

namespace App\Http\Requests\Shared;

use App\Http\Requests\Concerns\UuidRouteRequest;

class RouteImageIdRequest extends UuidRouteRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /** @return list<string> */
    protected function routeUuidParameters(): array
    {
        return ['imageId'];
    }
}
