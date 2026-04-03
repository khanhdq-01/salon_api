<?php

namespace App\Http\Resources\Api\V1\Owner;

use App\Models\Package;
use App\Support\TrialPackage;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OwnerPackagePlanResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $isTrial = (bool) ($this->is_trial ?? TrialPackage::isTrial($this->resource));

        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => ucfirst((string) $this->type),
            'price' => $this->price,
            'description' => $this->description,
            'billing_period' => $this->billing_period ?? Package::BILLING_1_MONTH,
            'billing_period_label' => $this->billingPeriodLabel(),
            'bookings_limit_label' => $this->bookingsLimitLabel(),
            'max_staff' => $this->max_staff,
            'max_services' => $this->max_services,
            'max_bookings_per_month' => $this->max_bookings_per_month,
            'is_trial' => $isTrial,
            'trial_used' => (bool) ($this->trial_used ?? false),
            'selectable' => (bool) ($this->selectable ?? true),
        ];
    }
}
