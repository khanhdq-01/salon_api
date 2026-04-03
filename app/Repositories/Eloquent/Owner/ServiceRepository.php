<?php

namespace App\Repositories\Eloquent\Owner;

use App\Repositories\Interfaces\Owner\ServiceRepositoryInterface;
use App\Models\Service;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ServiceRepository implements ServiceRepositoryInterface
{
    public function __construct(
        protected Service $model
    ) {}

    public function findById(string $id, array $relations = []): ?Service
    {
        $relations = $relations ?: ['styleOptions'];

        return $this->model->newQuery()->with($relations)->find($id);
    }

    public function findActiveByIdsForSalon(array $ids, string $salonId): Collection
    {
        return $this->model->newQuery()
            ->where('salon_id', $salonId)
            ->whereIn('id', $ids)
            ->active()
            ->get();
    }

    public function paginate(array $filters): LengthAwarePaginator
    {
        $query = $this->model->newQuery();
        $this->applyFilters($query, $filters);

        $query->with(['styleOptions' => function ($relation) use ($filters) {
            if ($filters['public_only'] ?? false) {
                $relation->where('is_active', true);
            }
            $relation->orderBy('sort_order');
        }]);

        return $query
            ->orderBy('name')
            ->paginate(perPage: $filters['per_page'], page: $filters['page']);
    }

    public function create(array $data): Service
    {
        return $this->model->newQuery()->create($data);
    }

    public function update(Service $service, array $data): Service
    {
        $service->update($data);

        return $service->fresh();
    }

    public function delete(Service $service): bool
    {
        return (bool) $service->delete();
    }

    public function incrementBookingsCount(Service $service, int $by = 1): void
    {
        $service->increment('bookings_count', $by);
    }

    public function countBySalon(string $salonId): int
    {
        return $this->model->newQuery()
            ->where('salon_id', $salonId)
            ->count();
    }

    public function getMinDurationBySalonIds(array $salonIds): Collection
    {
        return $this->model->newQuery()
            ->active()
            ->whereIn('salon_id', $salonIds)
            ->selectRaw('salon_id, MIN(duration_minutes) as min_duration')
            ->groupBy('salon_id')
            ->pluck('min_duration', 'salon_id');
    }

    public function listPopular(int $limit): Collection
    {
        return $this->model->newQuery()
            ->active()
            ->whereHas('salon', fn (Builder $query) => $query->publiclyVisible())
            ->select([
                DB::raw('MIN(name) as name'),
                DB::raw('SUM(bookings_count) as bookings_count'),
                DB::raw('COUNT(DISTINCT salon_id) as salon_count'),
                DB::raw('MIN(price) as min_price'),
                DB::raw('MAX(price) as max_price'),
                DB::raw('MIN(duration_minutes) as min_duration'),
            ])
            ->groupBy(DB::raw('LOWER(TRIM(name))'))
            ->orderByDesc('bookings_count')
            ->orderByDesc('salon_count')
            ->orderBy('name')
            ->limit($limit)
            ->get();
    }

    protected function applyFilters(Builder $query, array $filters): void
    {
        if (! empty($filters['salon_id'])) {
            $query->where('salon_id', $filters['salon_id']);
        }

        if ($filters['public_only'] ?? false) {
            $query->active()->whereHas('salon', fn (Builder $q) => $q->publiclyVisible());
        }

        if ($filters['is_active'] !== null) {
            $query->where('is_active', $filters['is_active']);
        }

        if (! empty($filters['query'])) {
            $query->search($filters['query']);
        }
    }
}
