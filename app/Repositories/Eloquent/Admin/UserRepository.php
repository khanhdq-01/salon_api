<?php

namespace App\Repositories\Eloquent\Admin;

use App\Models\Role;
use App\Models\User;
use App\Repositories\Interfaces\Admin\UserRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class UserRepository implements UserRepositoryInterface
{
    public function __construct(
        protected User $model
    ) {}

    public function paginate(array $filters, array $relations, array $withCount): LengthAwarePaginator
    {
        $query = $this->model->newQuery()
            ->with($relations)
            ->withCount($withCount);

        if (! empty($filters['q'])) {
            $term = '%' . $filters['q'] . '%';
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', $term)
                    ->orWhere('email', 'like', $term)
                    ->orWhere('phone', 'like', $term);
            });
        }

        if (! empty($filters['role_id'])) {
            $query->where('role_id', $filters['role_id']);
        }

        if (! empty($filters['role'])) {
            $query->whereHas('role', fn ($q) => $q->where('name', $filters['role']));
        }

        if (isset($filters['is_locked'])) {
            if ($filters['is_locked']) {
                $query->where('status', User::STATUS_SUSPENDED);
            } else {
                $query->where('status', '!=', User::STATUS_SUSPENDED);
            }
        }

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        $page = max(1, (int) ($filters['page'] ?? 1));
        $perPage = min(100, max(1, (int) ($filters['limit'] ?? $filters['per_page'] ?? 15)));

        return $query
            ->orderByDesc('created_at')
            ->paginate(perPage: $perPage, page: $page);
    }

    public function findById(string $id): ?User
    {
        return $this->model->newQuery()->find($id);
    }

    public function findByIdWithRole(string $id): ?User
    {
        return $this->model->newQuery()->with('role')->find($id);
    }

    public function update(User $user, array $data): User
    {
        $user->update($data);

        return $user->fresh();
    }

    public function delete(User $user): bool
    {
        return (bool) $user->delete();
    }

    public function countAll(): int
    {
        return $this->model->newQuery()->count();
    }

    public function countByRoleName(string $roleName): int
    {
        return $this->model->newQuery()
            ->whereHas('role', fn (Builder $q) => $q->where('name', $roleName))
            ->count();
    }

    public function countSuspended(): int
    {
        return $this->model->newQuery()->where('status', User::STATUS_SUSPENDED)->count();
    }

    public function getSuspendedAlerts(int $limit): Collection
    {
        return $this->model->newQuery()
            ->where('status', User::STATUS_SUSPENDED)
            ->latest()
            ->limit($limit)
            ->get(['id', 'name', 'email', 'created_at']);
    }
}
