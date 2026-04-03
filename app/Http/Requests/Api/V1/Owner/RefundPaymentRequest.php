<?php

namespace App\Http\Requests\Api\V1\Owner;

use App\Http\Requests\Concerns\UuidRouteRequest;

class RefundPaymentRequest extends UuidRouteRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /** @return list<string> */
    protected function routeUuidParameters(): array
    {
        return ['bookingId'];
    }
}
