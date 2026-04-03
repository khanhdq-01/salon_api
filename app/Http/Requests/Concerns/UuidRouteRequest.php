<?php

namespace App\Http\Requests\Concerns;

use Illuminate\Foundation\Http\FormRequest;

abstract class UuidRouteRequest extends FormRequest
{
    use ValidatesRouteUuids;

    protected function prepareForValidation(): void
    {
        $this->prepareRouteUuidValidation();
    }

    public function rules(): array
    {
        return $this->routeUuidRules();
    }
}
