<?php

namespace App\Repositories\Eloquent\Admin;

use App\Models\Package;
use App\Repositories\Interfaces\Admin\PackageRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class PackageRepository implements PackageRepositoryInterface
{
    public function __construct(
        protected Package $model
    ) {}

    public function paginate(array $filters): LengthAwarePaginator
    {
        $query = $this->model->newQuery();
        $this->applyFilters($query, $filters);

        $page = max(1, (int) ($filters['page'] ?? 1));
        $perPage = min(100, max(1, (int) ($filters['limit'] ?? $filters['per_page'] ?? 15)));

        return $query->orderByDesc('created_at')->paginate(perPage: $perPage, page: $page);
    }

    public function findById(string $id): ?Package
    {
        return $this->model->newQuery()->find($id);
    }

    public function findActiveById(string $id): ?Package
    {
        return $this->model->newQuery()->active()->find($id);
    }

    public function create(array $data): Package
    {
        return $this->model->newQuery()->create($data);
    }

    public function update(Package $package, array $data): Package
    {
        $package->update($data);

        return $package->fresh();
    }

    public function delete(Package $package): bool
    {
        return (bool) $package->delete();
    }

    protected function applyFilters(Builder $query, array $filters): void
    {
        if (! empty($filters['q'])) {
            $term = '%' . $filters['q'] . '%';
            $query->where(function (Builder $q) use ($term) {
                $q->where('name', 'like', $term)
                    ->orWhere('description', 'like', $term);
            });
        }

        if (! empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }
    }
}
