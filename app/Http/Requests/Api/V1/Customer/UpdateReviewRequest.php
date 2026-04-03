<?php

namespace App\Http\Requests\Api\V1\Customer;

use App\Http\Requests\Concerns\ValidatesRouteUuids;
use Illuminate\Foundation\Http\FormRequest;

class UpdateReviewRequest extends FormRequest
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
            'rating' => ['sometimes', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:2000'],
        ]);
    }
}
