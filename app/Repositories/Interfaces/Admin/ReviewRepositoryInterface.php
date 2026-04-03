<?php

namespace App\Repositories\Interfaces\Admin;

use App\Models\Review;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ReviewRepositoryInterface
{
    public function paginate(array $filters): LengthAwarePaginator;

    public function findByIdWithTrashed(string $id, array $relations = []): ?Review;

    public function findTrashedById(string $id): ?Review;

    public function softDelete(Review $review): bool;

    public function restore(Review $review): Review;

    public function forceDelete(Review $review): bool;
}
