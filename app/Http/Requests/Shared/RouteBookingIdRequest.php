<?php

namespace App\Http\Requests\Shared;

use App\Http\Requests\Concerns\UuidRouteRequest;

class RouteBookingIdRequest extends UuidRouteRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return list<string> */
    protected function routeUuidParameters(): array
    {
        return ['bookingId'];
    }
}
