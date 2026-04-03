<?php

namespace App\Http\Requests\Api\V1\Customer;

use App\Http\Requests\Concerns\ValidatesRouteUuids;
use Illuminate\Foundation\Http\FormRequest;

class PaymentWebhookRequest extends FormRequest
{
    use ValidatesRouteUuids;

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $provider = $this->route('provider');

        if (is_string($provider)) {
            $this->merge(['provider' => $provider]);
        }
    }

    /** @return list<string> */
    protected function routeUuidParameters(): array
    {
        return [];
    }

    public function rules(): array
    {
        return [
            'provider' => ['required', 'string', 'max:50', 'regex:/^[a-z0-9_-]+$/i'],
        ];
    }

    /** @return array<string, mixed> */
    public function webhookPayload(): array
    {
        return $this->except(['provider']);
    }
}
