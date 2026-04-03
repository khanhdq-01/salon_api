<?php

namespace App\Repositories\Interfaces\Customer;

use App\Models\Review;
use App\Models\ReviewReport;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ReviewRepositoryInterface
{
    public function findById(string $id, array $relations = []): ?Review;

    public function findByBookingId(string $bookingId): ?Review;

    public function paginateBySalon(string $salonId, array $filters): LengthAwarePaginator;

    public function create(array $data): Review;

    public function update(Review $review, array $data): Review;

    public function delete(Review $review): bool;

    public function createReport(Review $review, array $data, string $reporterId): ReviewReport;

    public function getSalonRatingStats(string $salonId): array;

    public function getSalonRatingDistribution(string $salonId): array;

    public function updateSalonRating(string $salonId, float $avg, int $count): void;
}
