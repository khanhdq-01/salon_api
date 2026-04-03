<?php

namespace App\Http\Requests\Api\V1\Customer;

use App\Http\Requests\Concerns\ValidatesRouteUuids;
use Illuminate\Foundation\Http\FormRequest;

class StoreSalonReviewRequest extends FormRequest
{
    use ValidatesRouteUuids;

    public function authorize(): bool
    {
        return $this->user()?->isCustomer() ?? false;
    }

    protected function prepareForValidation(): void
    {
        $this->prepareRouteUuidValidation();
    }

    /** @return list<string> */
    protected function routeUuidParameters(): array
    {
        return ['salonId'];
    }

    public function rules(): array
    {
        return array_merge($this->routeUuidRules(), [
            'booking_id' => ['nullable', 'uuid', 'exists:bookings,id'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['required', 'string', 'min:1', 'max:2000'],
        ]);
    }
}
