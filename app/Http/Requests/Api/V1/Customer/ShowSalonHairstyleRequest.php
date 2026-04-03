<?php

namespace App\Http\Requests\Api\V1\Customer;

use App\Http\Requests\Concerns\UuidRouteRequest;

class ShowSalonHairstyleRequest extends UuidRouteRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return list<string> */
    protected function routeUuidParameters(): array
    {
        return ['salonId', 'styleId'];
    }
}
