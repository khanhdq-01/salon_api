<?php

namespace App\Contracts\Services\Admin;

interface AdminReviewManagementServiceInterface
{
    public function listReviews(array $filters): mixed;

    public function getReview(string $id): mixed;

    public function hideReview(string $id): bool;

    public function showReview(string $id): mixed;

    public function deleteReview(string $id): bool;

    public function listReports(array $filters): mixed;

    public function resolveReport(string $id): mixed;
}
