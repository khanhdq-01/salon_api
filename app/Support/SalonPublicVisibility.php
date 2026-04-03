<?php

namespace App\Support;

use App\Models\Salon;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class SalonPublicVisibility
{
    public static function isPublic(Salon $salon): bool
    {
        if ($salon->approval_status !== Salon::APPROVAL_APPROVED) {
            return false;
        }

        if ($salon->is_locked || $salon->status !== Salon::STATUS_OPEN) {
            return false;
        }

        if ($salon->trashed()) {
            return false;
        }

        if ($salon->relationLoaded('owner')) {
            if ($salon->owner?->status !== User::STATUS_ACTIVE) {
                return false;
            }
        } elseif (! User::query()->whereKey($salon->owner_id)->where('status', User::STATUS_ACTIVE)->exists()) {
            return false;
        }

        $subscription = SubscriptionExpiry::findOwnerSubscription($salon->owner_id);

        if (! $subscription) {
            return false;
        }

        return SubscriptionExpiry::resolveEffectiveStatus($subscription) === Subscription::STATUS_ACTIVE;
    }

    public static function applyPublicScope(Builder $query): Builder
    {
        $today = now()->toDateString();

        return $query
            ->where('approval_status', Salon::APPROVAL_APPROVED)
            ->where('is_locked', false)
            ->where('status', Salon::STATUS_OPEN)
            ->whereHas('owner', fn (Builder $ownerQuery) => $ownerQuery
                ->where('status', User::STATUS_ACTIVE))
            ->whereHas('owner.subscriptions', function (Builder $subscriptionQuery) use ($today) {
                $subscriptionQuery
                    ->where('status', Subscription::STATUS_ACTIVE)
                    ->whereNotNull('end_date')
                    ->whereDate('end_date', '>=', $today);
            });
    }
}
