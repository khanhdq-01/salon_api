<?php

namespace App\Repositories\Eloquent\Admin;

use App\Models\AuditLog;
use App\Repositories\Interfaces\Admin\AuditLogRepositoryInterface;
use App\Support\AuditLogPresenter;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AuditLogRepository implements AuditLogRepositoryInterface
{
    public function __construct(
        protected AuditLog $model
    ) {}

    public function paginate(array $filters): LengthAwarePaginator
    {
        $query = $this->model->newQuery()->with(['user:id,name,email,role_id', 'user.role:id,name,display_name']);

        $this->applyFilters($query, $filters);

        $page = max(1, (int) ($filters['page'] ?? 1));
        $perPage = min(100, max(1, (int) ($filters['limit'] ?? $filters['per_page'] ?? 15)));

        return $query->orderByDesc('created_at')->paginate(perPage: $perPage, page: $page);
    }

    public function findById(string $id): ?AuditLog
    {
        return $this->model->newQuery()
            ->with(['user:id,name,email,role_id', 'user.role:id,name,display_name'])
            ->find($id);
    }

    public function deleteAll(): int
    {
        return $this->model->newQuery()->delete();
    }

    public function getRecent(int $limit): Collection
    {
        return $this->model->newQuery()
            ->with(['user:id,name,email,role_id', 'user.role:id,name,display_name'])
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }

    protected function applyFilters(Builder $query, array $filters): void
    {
        if (! empty($filters['q'])) {
            $term = '%'.$filters['q'].'%';
            $query->where(function (Builder $q) use ($term, $filters) {
                $q->where('action', 'like', $term)
                    ->orWhere('target_type', 'like', $term)
                    ->orWhere('target_id', 'like', $term)
                    ->orWhere('ip_address', 'like', $term)
                    ->orWhere('details', 'like', $term)
                    ->orWhereHas('user', fn (Builder $user) => $user
                        ->where('name', 'like', $term)
                        ->orWhere('email', 'like', $term));

                if ($this->looksLikeUuidSearch($filters['q'])) {
                    $q->orWhere('target_id', $filters['q']);
                }
            });
        }

        if (! empty($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (! empty($filters['role'])) {
            $this->applyRoleFilter($query, strtolower($filters['role']));
        }

        if (! empty($filters['module'])) {
            AuditLogPresenter::applyModuleFilter($query, $filters['module']);
        }

        if (! empty($filters['action'])) {
            AuditLogPresenter::applyActionFilter($query, $filters['action']);
        }

        if (! empty($filters['salon_id'])) {
            $this->applySalonFilter($query, $filters['salon_id']);
        }

        if (! empty($filters['target_type'])) {
            $query->where('target_type', strtolower($filters['target_type']));
        }

        if (! empty($filters['status'])) {
            $query->where('status', strtolower($filters['status']));
        }

        if (! empty($filters['start_date'])) {
            $query->whereDate('created_at', '>=', $filters['start_date']);
        }

        if (! empty($filters['end_date'])) {
            $query->whereDate('created_at', '<=', $filters['end_date']);
        }
    }

    protected function applyRoleFilter(Builder $query, string $role): void
    {
        if ($role === 'system') {
            $query->whereNull('user_id');

            return;
        }

        $query->where(function (Builder $builder) use ($role) {
            $builder->whereHas('user.role', fn (Builder $roleQuery) => $roleQuery->where('name', $role))
                ->orWhere(function (Builder $guest) use ($role) {
                    $guest->whereNull('user_id')
                        ->where('details->portal', $role);
                });
        });
    }

    protected function applySalonFilter(Builder $query, string $salonId): void
    {
        $bookingIds = DB::table('bookings')
            ->where('salon_id', $salonId)
            ->pluck('id');

        $query->where(function (Builder $builder) use ($salonId, $bookingIds) {
            $builder->where(function (Builder $salonTarget) use ($salonId) {
                $salonTarget->where('target_type', 'salon')
                    ->where('target_id', $salonId);
            });

            if ($bookingIds->isNotEmpty()) {
                $builder->orWhere(function (Builder $bookingTarget) use ($bookingIds) {
                    $bookingTarget->where('target_type', 'booking')
                        ->whereIn('target_id', $bookingIds);
                });
            }

            $builder->orWhere('details->salon_id', $salonId);
        });
    }

    protected function looksLikeUuidSearch(string $term): bool
    {
        return (bool) preg_match('/^[0-9a-f-]{8,}$/i', trim($term));
    }
}
