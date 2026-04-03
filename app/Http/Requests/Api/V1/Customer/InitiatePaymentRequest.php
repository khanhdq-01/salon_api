<?php

namespace App\Http\Requests\Api\V1\Customer;

use App\Http\Requests\Concerns\ValidatesRouteUuids;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class InitiatePaymentRequest extends FormRequest
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
        return ['bookingId'];
    }

    public function rules(): array
    {
        return array_merge($this->routeUuidRules(), [
            'method' => ['required', 'string', Rule::in(['cash', 'momo', 'zalopay', 'vnpay'])],
            'return_url' => ['nullable', 'url'],
        ]);
    }
}
