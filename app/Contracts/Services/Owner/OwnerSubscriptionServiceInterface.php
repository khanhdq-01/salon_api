<?php

namespace App\Contracts\Services\Owner;

use App\Models\User;

interface OwnerSubscriptionServiceInterface
{
    public function getSubscription(User $owner): array;

    /**
     * @return array<int, \App\Models\Package>
     */
    public function getAvailablePackages(User $owner): array;

    /**
     * @return array<int, \App\Models\Package>
     */
    public function getPlans(User $owner): array;

    public function submitPayment(User $owner, array $data): array;

    public function upgrade(User $owner, array $data): array;
}
