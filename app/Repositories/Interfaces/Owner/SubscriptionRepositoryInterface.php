<?php

namespace App\Repositories\Interfaces\Owner;

use App\Models\Subscription;
use Illuminate\Support\Collection;

interface SubscriptionRepositoryInterface
{
    public function findResolvableForOwner(string $ownerId): ?Subscription;

    public function findEffectiveForOwner(string $ownerId): ?Subscription;

    public function findActiveForOwner(string $ownerId): ?Subscription;

    public function getActiveExpiringOnDate(string $date, array $relations): Collection;
}
