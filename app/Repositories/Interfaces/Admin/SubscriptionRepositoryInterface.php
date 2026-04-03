<?php

namespace App\Repositories\Interfaces\Admin;

use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface SubscriptionRepositoryInterface
{
    public function paginate(array $filters, array $relations): LengthAwarePaginator;

    public function findById(string $id): ?Subscription;

    public function create(array $data): Subscription;

    public function update(Subscription $subscription, array $data): Subscription;

    public function delete(Subscription $subscription): bool;

    public function countActive(): int;

    public function countPendingApprovalWithRequestedPackage(): int;

    public function countAwaitingPaymentWithRequestedPackage(): int;

    public function sumApprovedAmountBetween(Carbon $start, Carbon $end): int;

    public function countApprovedBetween(Carbon $start, Carbon $end): int;

    public function countExpiringBetween(Carbon $today, Carbon $threshold): int;

    public function countExpired(Carbon $today): int;

    public function getPendingApprovalAlerts(int $limit): Collection;

    public function getAwaitingPaymentAlerts(int $limit): Collection;

    public function getActive(array $relations, array $columns = ['*']): Collection;

    public function countActiveInDateRange(Carbon $monthStart, Carbon $monthEnd): int;

    public function getApprovedBetweenWithPackage(Carbon $start, Carbon $end, array $columns = ['*']): Collection;

    public function getApprovedBetweenWithOwnerAndPackage(Carbon $start, Carbon $end, array $columns = ['*']): Collection;

    public function getActiveExpiringBetweenWithPackage(Carbon $today, Carbon $threshold, array $columns = ['*']): Collection;

    public function subscriptionRelations(): array;

    public function subscriptionDetailRelations(): array;
}
