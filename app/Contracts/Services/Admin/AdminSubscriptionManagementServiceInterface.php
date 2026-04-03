<?php

namespace App\Contracts\Services\Admin;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface AdminSubscriptionManagementServiceInterface
{
    public function listSubscriptions(array $filters): LengthAwarePaginator;

    public function createSubscription(array $data): Subscription;

    public function getSubscription(string $id): Subscription;

    public function updateSubscription(string $id, array $data): Subscription;

    public function approveUpgrade(string $id, User $admin): Subscription;

    public function rejectUpgrade(string $id, User $admin): Subscription;

    public function deleteSubscription(string $id): bool;
}
