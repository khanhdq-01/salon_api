<?php

namespace App\Http\Resources\Api\V1\Customer;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RegisterSuccessResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'user' => new UserResource($this->resource['user'] ?? null),
            'verification_required' => true,
            'message' => 'Đăng ký thành công. Vui lòng kiểm tra email để xác thực tài khoản.',
        ];
    }
}
