<?php

namespace App\Support;

use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class SubscriptionExpiry
{
    public static function findOwnerSubscription(string $ownerId): ?Subscription
    {
        return Subscription::query()
            ->forOwner($ownerId)
            ->where('status', '!=', Subscription::STATUS_CANCELLED)
            ->orderByRaw("CASE WHEN status = ? THEN 0 ELSE 1 END", [Subscription::STATUS_ACTIVE])
            ->orderByDesc('end_date')
            ->first();
    }

    public static function isExpired(Subscription $subscription): bool
    {
        if ($subscription->status === Subscription::STATUS_CANCELLED) {
            return false;
        }

        if ($subscription->status === Subscription::STATUS_EXPIRED) {
            return true;
        }

        if (in_array($subscription->status, Subscription::AWAITING_REVIEW_STATUSES, true)) {
            return false;
        }

        if ($subscription->status === Subscription::STATUS_AWAITING_PAYMENT) {
            return false;
        }

        if (! $subscription->end_date) {
            return false;
        }

        return Carbon::today()->gt(Carbon::parse($subscription->end_date));
    }

    public static function resolveEffectiveStatus(Subscription $subscription): string
    {
        if ($subscription->status === Subscription::STATUS_CANCELLED) {
            return Subscription::STATUS_CANCELLED;
        }

        if (in_array($subscription->status, Subscription::AWAITING_REVIEW_STATUSES, true)) {
            return $subscription->status === Subscription::STATUS_PENDING_APPROVAL
                ? Subscription::STATUS_PENDING_APPROVAL
                : Subscription::STATUS_AWAITING_PAYMENT;
        }

        if (self::isExpired($subscription)) {
            return Subscription::STATUS_EXPIRED;
        }

        return $subscription->status;
    }

    public static function ownerSubscriptionIsExpired(string $ownerId): bool
    {
        return ! self::ownerHasActiveSubscription($ownerId);
    }

    public static function ownerHasActiveSubscription(string $ownerId): bool
    {
        $subscription = self::findOwnerSubscription($ownerId);

        if (! $subscription) {
            return false;
        }

        return self::resolveEffectiveStatus($subscription) === Subscription::STATUS_ACTIVE;
    }

    public static function syncExpiredSubscriptions(): void
    {
        $updated = Subscription::query()
            ->whereNotIn('status', [
                Subscription::STATUS_CANCELLED,
                Subscription::STATUS_EXPIRED,
                Subscription::STATUS_PENDING_APPROVAL,
                Subscription::STATUS_AWAITING_PAYMENT,
            ])
            ->whereDate('end_date', '<', Carbon::today())
            ->update(['status' => Subscription::STATUS_EXPIRED]);

        if ($updated > 0) {
            Cache::increment('salons:list:version');
        }
    }
}
