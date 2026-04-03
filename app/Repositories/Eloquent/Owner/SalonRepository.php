<?php

namespace App\Repositories\Eloquent\Owner;

use App\Repositories\Interfaces\Owner\SalonRepositoryInterface;
use App\Models\Salon;
use App\Support\SalonSearchQuery;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class SalonRepository implements SalonRepositoryInterface
{
    public function __construct(
        protected Salon $model
    ) {}

    public function findById(string $id, array $relations = [], bool $withTrashed = false): ?Salon
    {
        $query = $this->model->newQuery()->with($relations);

        if ($withTrashed) {
            $query->withTrashed();
        }

        return $query->find($id);
    }

    public function findByOwnerId(string $ownerId, bool $withTrashed = false): ?Salon
    {
        $query = $this->model->newQuery()->where('owner_id', $ownerId);

        if ($withTrashed) {
            $query->withTrashed();
        }

        return $query->first();
    }

    public function create(array $data): Salon
    {
        return $this->model->newQuery()->create($data);
    }

    public function update(Salon $salon, array $data): Salon
    {
        $salon->update($data);

        return $salon->fresh(['owner:id,name,email,phone', 'images' => fn ($query) => $query->orderByDesc('created_at')]);
    }

    public function delete(Salon $salon): bool
    {
        return (bool) $salon->delete();
    }

    public function paginate(array $filters): LengthAwarePaginator
    {
        $query = $this->model->newQuery()
            ->with([
                'owner:id,name,email,phone,status',
                'owner.subscriptions' => fn ($subscriptionQuery) => $subscriptionQuery
                    ->where('status', '!=', \App\Models\Subscription::STATUS_CANCELLED)
                    ->orderByDesc('end_date'),
                'requestedPackage:id,name,price,billing_period,max_staff,max_services,max_bookings_per_month',
                'images' => fn ($imageQuery) => $imageQuery->orderByDesc('created_at'),
            ])
            ->withCount(['services', 'staff']);

        if ($filters['with_trashed'] ?? false) {
            $query->withTrashed();
        }

        $this->applyFilters($query, $filters);

        if (! empty($filters['query'])) {
            SalonSearchQuery::orderByRelevance($query, $filters['query']);
        } elseif (($filters['sort'] ?? null) === 'rating') {
            $query
                ->orderByDesc('rating_avg')
                ->orderByDesc('bookings_count')
                ->orderByDesc('created_at');
        } else {
            $query->orderByDesc('created_at');
        }

        return $query
            ->paginate(perPage: $filters['per_page'], page: $filters['page']);
    }

    public function getPubliclyVisibleBasic(): Collection
    {
        return $this->model->newQuery()
            ->publiclyVisible()
            ->get(['id', 'open_time', 'close_time']);
    }

    protected function applyFilters(Builder $query, array $filters): void
    {
        if ($filters['public_only'] ?? false) {
            $query->publiclyVisible();
        }

        if (! empty($filters['query'])) {
            SalonSearchQuery::apply($query, $filters['query']);
        }

        if ($filters['rating_min'] !== null) {
            $query->where('rating_avg', '>=', $filters['rating_min']);
        }

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (! empty($filters['approval_status'])) {
            $query->where('approval_status', $filters['approval_status']);
        }

        if (! empty($filters['owner_id'])) {
            $query->where('owner_id', $filters['owner_id']);
        }

        if ($filters['lat'] !== null && $filters['lng'] !== null && $filters['distance_km'] !== null) {
            $query->whereNotNull('lat')->whereNotNull('lng')->whereRaw(
                '(6371 * acos(cos(radians(?)) * cos(radians(lat)) * cos(radians(lng) - radians(?)) + sin(radians(?)) * sin(radians(lat)))) <= ?',
                [$filters['lat'], $filters['lng'], $filters['lat'], $filters['distance_km']]
            );
        }

        if ($filters['available_today'] ?? false) {
            $availableSalonIds = $filters['available_salon_ids'] ?? [];

            if ($availableSalonIds === []) {
                $query->whereRaw('1 = 0');
            } else {
                $query->whereIn('id', $availableSalonIds);
            }
        }
    }
}
