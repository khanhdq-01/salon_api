<?php

namespace App\Http\Resources\Api\V1\Customer;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class BookingCollection extends ResourceCollection
{
    public $collects = BookingResource::class;

    public function toArray(Request $request): array
    {
        return [
            'items' => $this->collection,
        ];
    }
}
