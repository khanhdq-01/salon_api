<?php

namespace App\Http\Resources\Api\V1\Owner;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OwnerPaymentInstructionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $data = is_array($this->resource) ? $this->resource : [];

        return [
            'id' => $data['id'] ?? null,
            'title' => $data['title'] ?? null,
            'bank_name' => $data['bank_name'] ?? null,
            'account_number' => $data['account_number'] ?? null,
            'account_holder' => $data['account_holder'] ?? null,
            'transfer_content' => $data['transfer_content'] ?? null,
            'qr_code_image' => $data['qr_code_image'] ?? null,
            'qr_code_image_url' => $data['qr_code_image_url'] ?? null,
            'content' => $data['content'] ?? null,
            'updated_at' => $data['updated_at'] ?? null,
        ];
    }
}
