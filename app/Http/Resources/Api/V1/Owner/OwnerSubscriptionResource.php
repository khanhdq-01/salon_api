<?php

namespace App\Http\Resources\Api\V1\Owner;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OwnerSubscriptionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $data = is_array($this->resource) ? $this->resource : [];

        return [
            'subscription_id' => $data['subscription_id'] ?? null,
            'salon_approval_status' => $data['salon_approval_status'] ?? null,
            'is_salon_public' => $data['is_salon_public'] ?? false,
            'plan' => $data['plan'] ?? [],
            'expire_date' => $data['expire_date'] ?? null,
            'days_left' => $data['days_left'] ?? 0,
            'auto_renew' => $data['auto_renew'] ?? false,
            'status' => $data['status'] ?? null,
            'requires_initial_payment' => $data['requires_initial_payment'] ?? false,
            'initial_payment_submitted' => $data['initial_payment_submitted'] ?? false,
            'pending_upgrade' => $data['pending_upgrade'] ?? null,
            'usage' => $data['usage'] ?? [],
        ];
    }
}
