<?php

namespace App\Http\Requests\Api\V1\Admin;

use App\Http\Requests\Concerns\ValidatesRouteUuids;
use App\Models\Booking;
use Illuminate\Validation\Rule;

class AdminBookingStatusRequest extends AdminAuthorizedRequest
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
            'status' => ['required', Rule::in([
                Booking::STATUS_PENDING,
                Booking::STATUS_CONFIRMED,
                Booking::STATUS_COMPLETED,
                Booking::STATUS_CANCELLED,
            ])],
            'reason' => ['nullable', 'string', 'max:500'],
        ]);
    }
}
