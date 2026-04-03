<?php

namespace App\Http\Requests\Api\V1\Staff;

use App\Http\Requests\Concerns\UuidRouteRequest;

class CompleteStaffBookingRequest extends UuidRouteRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /** @return list<string> */
    protected function routeUuidParameters(): array
    {
        return ['id'];
    }
}
