<?php

namespace App\Http\Resources\Api\V1\Customer;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmailVerificationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'verified' => true,
            'user' => new UserResource($this->resource),
            'message' => 'Email đã được xác thực thành công. Bạn có thể đăng nhập.',
        ];
    }
}
