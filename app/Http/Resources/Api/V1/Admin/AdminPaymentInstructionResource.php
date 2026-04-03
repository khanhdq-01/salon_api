<?php

namespace App\Http\Resources\Api\V1\Admin;

use App\Models\PaymentInstruction;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminPaymentInstructionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'bank_name' => $this->bank_name,
            'account_number' => $this->account_number,
            'account_holder' => $this->account_holder,
            'transfer_content' => $this->transfer_content,
            'qr_code_image' => $this->qr_code_image,
            'qr_code_image_url' => $this->resolveStorageUrl($this->qr_code_image),
            'content' => $this->content,
            'status' => $this->formatStatusLabel(),
            'status_key' => $this->status,
            'is_active' => $this->isActive(),
            'updated_at' => $this->formatDateTime($this->updated_at),
            'created_at' => $this->formatDateTime($this->created_at),
        ];
    }

    private function formatStatusLabel(): string
    {
        return $this->status === PaymentInstruction::STATUS_ACTIVE ? 'Active' : 'Inactive';
    }

    private function formatDateTime(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof \DateTimeInterface) {
            return $value->format(DATE_ATOM);
        }

        return (string) $value;
    }

    private function resolveStorageUrl(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://') || str_starts_with($path, '/')) {
            return $path;
        }

        return '/storage/'.$path;
    }
}
