<?php

namespace App\Repositories\Eloquent\Admin;

use App\Models\Subscription;
use App\Repositories\Interfaces\Admin\SubscriptionRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class SubscriptionRepository implements SubscriptionRepositoryInterface
{
    public function __construct(
        protected Subscription $model
    ) {}

    public function paginate(array $filters, array $relations): LengthAwarePaginator
    {
        $query = $this->model->newQuery()->with($relations);

        $this->applyFilters($query, $filters);

        $page = max(1, (int) ($filters['page'] ?? 1));
        $perPage = min(100, max(1, (int) ($filters['limit'] ?? $filters['per_page'] ?? 15)));

        return $query->orderByDesc('created_at')->paginate(perPage: $perPage, page: $page);
    }

    public function findById(string $id): ?Subscription
    {
        return $this->model->newQuery()->find($id);
    }

    public function create(array $data): Subscription
    {
        return $this->model->newQuery()->create($data);
    }

    public function update(Subscription $subscription, array $data): Subscription
    {
        $subscription->update($data);

        return $subscription;
    }

    public function delete(Subscription $subscription): bool
    {
        return (bool) $subscription->delete();
    }

    public function countActive(): int
    {
        return $this->model->newQuery()->active()->count();
    }

    public function countPendingApprovalWithRequestedPackage(): int
    {
        return $this->model->newQuery()
            ->where('status', Subscription::STATUS_PENDING_APPROVAL)
            ->whereNotNull('requested_package_id')
            ->count();
    }

    public function countAwaitingPaymentWithRequestedPackage(): int
    {
        return $this->model->newQuery()
            ->where('status', Subscription::STATUS_AWAITING_PAYMENT)
            ->whereNotNull('requested_package_id')
            ->count();
    }

    public function sumApprovedAmountBetween(Carbon $start, Carbon $end): int
    {
        return (int) $this->model->newQuery()
            ->whereNotNull('approved_at')
            ->whereBetween('approved_at', [$start, $end])
            ->sum('approved_amount');
    }

    public function countApprovedBetween(Carbon $start, Carbon $end): int
    {
        return $this->model->newQuery()
            ->whereNotNull('approved_at')
            ->whereBetween('approved_at', [$start, $end])
            ->count();
    }

    public function countExpiringBetween(Carbon $today, Carbon $threshold): int
    {
        return $this->model->newQuery()
            ->where('status', Subscription::STATUS_ACTIVE)
            ->whereDate('end_date', '>=', $today)
            ->whereDate('end_date', '<=', $threshold)
            ->count();
    }

    public function countExpired(Carbon $today): int
    {
        return $this->model->newQuery()
            ->where(function ($query) use ($today) {
                $query->where('status', Subscription::STATUS_EXPIRED)
                    ->orWhere(function ($inner) use ($today) {
                        $inner->whereNotIn('status', [
                            Subscription::STATUS_CANCELLED,
                            Subscription::STATUS_PENDING_APPROVAL,
                            Subscription::STATUS_AWAITING_PAYMENT,
                        ])
                            ->whereDate('end_date', '<', $today);
                    });
            })
            ->count();
    }

    public function getPendingApprovalAlerts(int $limit): Collection
    {
        return $this->model->newQuery()
            ->with([
                'owner.ownedSalons' => fn ($query) => $query->withTrashed()->select('id', 'owner_id', 'name'),
                'package:id,name',
                'requestedPackage:id,name',
            ])
            ->where('status', Subscription::STATUS_PENDING_APPROVAL)
            ->whereNotNull('requested_package_id')
            ->orderByDesc('requested_at')
            ->limit($limit)
            ->get();
    }

    public function getAwaitingPaymentAlerts(int $limit): Collection
    {
        return $this->model->newQuery()
            ->with([
                'owner.ownedSalons' => fn ($query) => $query->withTrashed()->select('id', 'owner_id', 'name'),
                'requestedPackage:id,name',
            ])
            ->where('status', Subscription::STATUS_AWAITING_PAYMENT)
            ->whereNotNull('requested_package_id')
            ->orderByDesc('requested_at')
            ->limit($limit)
            ->get();
    }

    public function getActive(array $relations, array $columns = ['*']): Collection
    {
        return $this->model->newQuery()
            ->active()
            ->with($relations)
            ->get($columns);
    }

    public function countActiveInDateRange(Carbon $monthStart, Carbon $monthEnd): int
    {
        return $this->model->newQuery()
            ->whereDate('start_date', '<=', $monthEnd->toDateString())
            ->whereDate('end_date', '>=', $monthStart->toDateString())
            ->whereIn('status', [
                Subscription::STATUS_ACTIVE,
                Subscription::STATUS_EXPIRED,
            ])
            ->count();
    }

    public function getApprovedBetweenWithPackage(Carbon $start, Carbon $end, array $columns = ['*']): Collection
    {
        return $this->model->newQuery()
            ->whereNotNull('approved_at')
            ->whereBetween('approved_at', [$start, $end])
            ->with('package:id,name')
            ->get($columns);
    }

    public function getApprovedBetweenWithOwnerAndPackage(Carbon $start, Carbon $end, array $columns = ['*']): Collection
    {
        return $this->model->newQuery()
            ->whereNotNull('approved_at')
            ->whereBetween('approved_at', [$start, $end])
            ->with([
                'owner.ownedSalons' => fn ($query) => $query->withTrashed()->select('id', 'owner_id', 'name'),
                'package:id,name',
            ])
            ->get($columns);
    }

    public function getActiveExpiringBetweenWithPackage(Carbon $today, Carbon $threshold, array $columns = ['*']): Collection
    {
        return $this->model->newQuery()
            ->active()
            ->whereDate('end_date', '>=', $today)
            ->whereDate('end_date', '<=', $threshold)
            ->with('package:id,price,billing_period')
            ->get($columns);
    }

    public function subscriptionRelations(): array
    {
        return [
            'owner:id,name,email',
            'owner.ownedSalons' => fn ($query) => $query
                ->withTrashed()
                ->select('id', 'owner_id', 'name'),
            'package:id,name,type',
            'requestedPackage:id,name,type',
        ];
    }

    public function subscriptionDetailRelations(): array
    {
        return [
            'owner:id,name,email,phone',
            'owner.ownedSalons' => fn ($query) => $query
                ->withTrashed()
                ->select('id', 'owner_id', 'name', 'address', 'phone', 'approval_status', 'status'),
            'package',
            'requestedPackage',
        ];
    }

    protected function applyFilters(Builder $query, array $filters): void
    {
        if (! empty($filters['q'])) {
            $term = '%'.$filters['q'].'%';
            $query->where(function (Builder $q) use ($term) {
                $q->whereHas('owner', fn (Builder $owner) => $owner
                    ->where('name', 'like', $term)
                    ->orWhere('email', 'like', $term))
                    ->orWhereHas('owner.ownedSalons', fn (Builder $salon) => $salon
                        ->where('name', 'like', $term))
                    ->orWhereHas('package', fn (Builder $pkg) => $pkg->where('name', 'like', $term))
                    ->orWhereHas('requestedPackage', fn (Builder $pkg) => $pkg->where('name', 'like', $term));
            });
        }

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (! empty($filters['owner_id'])) {
            $query->where('owner_id', $filters['owner_id']);
        }

        if (! empty($filters['package_id'])) {
            $query->where('package_id', $filters['package_id']);
        }
    }
}
