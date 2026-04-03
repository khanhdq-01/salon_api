<?php

namespace App\Http\Resources\Api\V1\Admin;

use App\Models\Role;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminUserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $subscription = $this->resolvePrimarySubscription();

        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'status' => $this->status,
            'role' => $this->whenLoaded('role', fn () => [
                'id' => $this->role->id,
                'name' => $this->role->name,
                'display_name' => $this->role->display_name,
            ]),
            'salons' => $this->whenLoaded('ownedSalons', fn () => $this->ownedSalons->map(fn ($salon) => [
                'id' => $salon->id,
                'name' => $salon->name,
                'approval_status' => $salon->approval_status,
                'is_locked' => (bool) $salon->is_locked,
                'deleted_at' => $salon->deleted_at?->toIso8601String(),
            ])),
            'subscription' => $this->when(
                $this->role?->name === Role::OWNER && $subscription,
                fn () => [
                    'id' => $subscription->id,
                    'package_id' => $subscription->package_id,
                    'package_name' => $subscription->package?->name,
                    'status' => $subscription->status,
                    'end_date' => $this->formatDate($subscription->end_date),
                ],
            ),
            'bookings_count' => $this->bookings_as_customer_count ?? 0,
            'created_at' => $this->formatDateTime($this->created_at),
            'last_login' => $this->formatDateTime($this->last_login),
        ];
    }

    private function resolvePrimarySubscription(): ?Subscription
    {
        if (! $this->relationLoaded('subscriptions')) {
            return null;
        }

        return $this->subscriptions->firstWhere('status', Subscription::STATUS_ACTIVE)
            ?? $this->subscriptions->first();
    }

    private function formatDate(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof \DateTimeInterface) {
            return $value->format('Y-m-d');
        }

        return substr((string) $value, 0, 10);
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
}
