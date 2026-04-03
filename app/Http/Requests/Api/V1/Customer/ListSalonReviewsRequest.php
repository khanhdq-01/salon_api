<?php

namespace App\Http\Requests\Api\V1\Customer;

use App\Http\Requests\Api\V1\Customer\ListReviewRequest;
use App\Http\Requests\Concerns\ValidatesRouteUuids;

class ListSalonReviewsRequest extends ListReviewRequest
{
    use ValidatesRouteUuids;

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
        return array_merge(parent::rules(), $this->routeUuidRules());
    }
}
