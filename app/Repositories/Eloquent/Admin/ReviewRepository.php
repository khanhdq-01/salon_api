<?php

namespace App\Repositories\Eloquent\Admin;

use App\Models\Review;
use App\Repositories\Interfaces\Admin\ReviewRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ReviewRepository implements ReviewRepositoryInterface
{
    public function __construct(
        protected Review $model
    ) {}

    public function paginate(array $filters): LengthAwarePaginator
    {
        $query = $this->model->newQuery()
            ->with(['customer:id,name', 'salon:id,name'])
            ->withTrashed();

        if (! empty($filters['salon_id'])) {
            $query->where('salon_id', $filters['salon_id']);
        }

        if (! empty($filters['q'])) {
            $term = '%' . $filters['q'] . '%';
            $query->where(function ($q) use ($term) {
                $q->where('comment', 'like', $term);
            });
        }

        if (isset($filters['hidden'])) {
            $filters['hidden'] ? $query->onlyTrashed() : $query->whereNull('deleted_at');
        }

        $page = max(1, (int) ($filters['page'] ?? 1));
        $perPage = min(100, max(1, (int) ($filters['per_page'] ?? 15)));

        return $query->orderByDesc('created_at')->paginate(perPage: $perPage, page: $page);
    }

    public function findByIdWithTrashed(string $id, array $relations = []): ?Review
    {
        return $this->model->newQuery()
            ->withTrashed()
            ->with($relations)
            ->find($id);
    }

    public function findTrashedById(string $id): ?Review
    {
        return $this->model->newQuery()->withTrashed()->find($id);
    }

    public function softDelete(Review $review): bool
    {
        return (bool) $review->delete();
    }

    public function restore(Review $review): Review
    {
        $review->restore();

        return $review->fresh(['customer:id,name', 'salon:id,name']);
    }

    public function forceDelete(Review $review): bool
    {
        return (bool) $review->forceDelete();
    }
}
