<?php

namespace App\Contracts\Services\Customer;

interface ReviewServiceInterface
{
    public function listSalonReviews(string $salonId, array $filters, ?\App\Models\User $actor = null): mixed;

    public function getSalonReviewSummary(string $salonId): array;

    public function getCustomerReviewContext(string $salonId, ?\App\Models\User $actor): array;

    public function createReview(string $bookingId, array $data, \App\Models\User $actor): mixed;

    public function createSalonReview(string $salonId, array $data, \App\Models\User $actor): mixed;

    public function updateReview(string $id, array $data, \App\Models\User $actor): mixed;

    public function deleteReview(string $id, \App\Models\User $actor): bool;

    public function reportReview(string $id, array $data, \App\Models\User $actor): mixed;
}
