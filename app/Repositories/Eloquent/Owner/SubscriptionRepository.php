<?php

namespace App\Repositories\Eloquent\Owner;

use App\Models\Subscription;
use App\Repositories\Interfaces\Owner\SubscriptionRepositoryInterface;
use Illuminate\Support\Collection;

class SubscriptionRepository implements SubscriptionRepositoryInterface
{
    public function __construct(
        protected Subscription $model
    ) {}

    public function findResolvableForOwner(string $ownerId): ?Subscription
    {
        return $this->model->newQuery()
            ->forOwner($ownerId)
            ->with(['package', 'requestedPackage'])
            ->where('status', '!=', Subscription::STATUS_CANCELLED)
            ->orderByRaw("CASE
                WHEN status IN ('".Subscription::STATUS_PENDING_APPROVAL."', '".Subscription::STATUS_AWAITING_PAYMENT."') THEN 0
                WHEN status IN ('".Subscription::STATUS_ACTIVE."', '".Subscription::STATUS_REJECTED."', '".Subscription::STATUS_APPROVED."', '".Subscription::STATUS_EXPIRED."') THEN 1
                ELSE 2
            END")
            ->orderByDesc('end_date')
            ->first();
    }

    public function findEffectiveForOwner(string $ownerId): ?Subscription
    {
        return $this->model->newQuery()
            ->forOwner($ownerId)
            ->effective()
            ->with('package')
            ->orderByDesc('end_date')
            ->first();
    }

    public function findActiveForOwner(string $ownerId): ?Subscription
    {
        return $this->model->newQuery()
            ->forOwner($ownerId)
            ->active()
            ->orderByDesc('end_date')
            ->first();
    }

    public function getActiveExpiringOnDate(string $date, array $relations): Collection
    {
        return $this->model->newQuery()
            ->active()
            ->whereDate('end_date', $date)
            ->with($relations)
            ->get();
    }
}
