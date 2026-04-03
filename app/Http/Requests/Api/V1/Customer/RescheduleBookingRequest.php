<?php

namespace App\Http\Requests\Api\V1\Customer;

use App\Http\Requests\Concerns\ValidatesRouteUuids;
use Illuminate\Foundation\Http\FormRequest;

class RescheduleBookingRequest extends FormRequest
{
    use ValidatesRouteUuids;

    public function authorize(): bool
    {
        return $this->user() !== null;
    }

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
            'date' => ['required', 'date', 'after_or_equal:today'],
            'time' => ['required', 'date_format:H:i'],
            'staff_id' => ['nullable', 'uuid', 'exists:staff,id'],
            'seat_id' => ['nullable', 'uuid', 'exists:seats,id'],
        ]);
    }
}
