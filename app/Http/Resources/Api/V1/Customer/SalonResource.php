<?php

namespace App\Http\Resources\Api\V1\Customer;

use App\Http\Resources\Api\V1\Customer\SalonImageResource;
use App\Http\Resources\Api\V1\Owner\SalonOwnerResource;
use App\Services\Owner\SalonTodayAvailabilityService;
use App\Support\SalonPublicVisibility;
use App\Support\SubscriptionExpiry;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SalonResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'address' => $this->address,
            'location' => [
                'lat' => $this->lat,
                'lng' => $this->lng,
            ],
            'phone' => $this->phone,
            'image_url' => $this->image_url,
            'gallery_images' => SalonImageResource::collection($this->whenLoaded('images')),
            'open_time' => $this->formatTime($this->open_time),
            'close_time' => $this->formatTime($this->close_time),
            'status' => $this->status,
            'approval_status' => $this->approval_status,
            'is_locked' => $this->is_locked,
            'is_public' => SalonPublicVisibility::isPublic($this->resource),
            'requested_package_id' => $this->requested_package_id,
            'requested_package' => $this->whenLoaded('requestedPackage', fn () => [
                'id' => $this->requestedPackage?->id,
                'name' => $this->requestedPackage?->name,
                'price' => $this->requestedPackage?->price,
            ]),
            'rating_avg' => (float) $this->rating_avg,
            'rating_count' => $this->rating_count,
            'bookings_count' => $this->bookings_count,
            'services_count' => $this->services_count ?? null,
            'staff_count' => $this->staff_count ?? null,
            'available_today' => $this->resolveAvailableToday(),
            'owner' => new SalonOwnerResource($this->whenLoaded('owner')),
            'subscription_expired' => $this->resolveSubscriptionExpired(),
            'subscription_status' => $this->resolveSubscriptionStatus(),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
            'deleted_at' => $this->deleted_at?->toIso8601String(),
        ];
    }

    private function formatTime(mixed $time): ?string
    {
        if ($time === null) {
            return null;
        }

        return substr((string) $time, 0, 5);
    }

    private function resolveOwnerSubscription(): ?\App\Models\Subscription
    {
        if ($this->relationLoaded('owner') && $this->owner?->relationLoaded('subscriptions')) {
            $subscription = $this->owner->subscriptions
                ->sortByDesc(fn ($sub) => $sub->end_date instanceof \DateTimeInterface
                    ? $sub->end_date->format('Y-m-d')
                    : (string) ($sub->end_date ?? ''))
                ->first();

            if ($subscription) {
                return $subscription;
            }
        }

        return SubscriptionExpiry::findOwnerSubscription($this->owner_id);
    }

    private function resolveAvailableToday(): bool
    {
        return app(SalonTodayAvailabilityService::class)->salonHasAvailabilityToday($this->resource);
    }

    private function resolveSubscriptionExpired(): bool
    {
        $subscription = $this->resolveOwnerSubscription();

        if (! $subscription) {
            return false;
        }

        return SubscriptionExpiry::isExpired($subscription);
    }

    private function resolveSubscriptionStatus(): string
    {
        $subscription = $this->resolveOwnerSubscription();

        if (! $subscription) {
            return 'none';
        }

        return SubscriptionExpiry::resolveEffectiveStatus($subscription);
    }
}
