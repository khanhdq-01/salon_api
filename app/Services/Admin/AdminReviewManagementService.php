<?php

namespace App\Services\Admin;

use App\Contracts\Services\Admin\AdminReviewManagementServiceInterface;
use App\Contracts\Services\Customer\ReviewServiceInterface;
use App\Exceptions\BusinessException;
use App\Models\Review;
use App\Models\ReviewReport;
use App\Models\User;
use App\Repositories\Interfaces\Admin\ReviewReportRepositoryInterface;
use App\Repositories\Interfaces\Admin\ReviewRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class AdminReviewManagementService implements AdminReviewManagementServiceInterface
{
    public function __construct(
        protected ReviewServiceInterface $reviewService,
        protected ReviewRepositoryInterface $reviewRepository,
        protected ReviewReportRepositoryInterface $reviewReportRepository
    ) {}

    public function listReviews(array $filters): LengthAwarePaginator
    {
        return $this->reviewRepository->paginate($filters);
    }

    public function getReview(string $id): Review
    {
        $review = $this->reviewRepository->findByIdWithTrashed($id, [
            'customer:id,name',
            'salon:id,name',
            'reports',
        ]);

        if (! $review) {
            throw new BusinessException('Review không tồn tại.', 'REVIEW_NOT_FOUND', 404);
        }

        return $review;
    }

    public function hideReview(string $id): bool
    {
        $review = $this->getReview($id);

        return $this->reviewRepository->softDelete($review);
    }

    public function showReview(string $id): Review
    {
        $review = $this->reviewRepository->findTrashedById($id);

        if (! $review) {
            throw new BusinessException('Review không tồn tại.', 'REVIEW_NOT_FOUND', 404);
        }

        return $this->reviewRepository->restore($review);
    }

    public function deleteReview(string $id): bool
    {
        $review = $this->getReview($id);

        return $this->reviewRepository->forceDelete($review);
    }

    public function listReports(array $filters): LengthAwarePaginator
    {
        return $this->reviewReportRepository->paginate($filters);
    }

    public function resolveReport(string $id): ReviewReport
    {
        $report = $this->reviewReportRepository->findById($id);

        if (! $report) {
            throw new BusinessException('Báo cáo không tồn tại.', 'REPORT_NOT_FOUND', 404);
        }

        return $this->reviewReportRepository->update($report, [
            'status' => ReviewReport::STATUS_RESOLVED,
        ]);
    }

    protected function adminUser(): User
    {
        $user = Auth::user();

        if (! $user instanceof User || ! $user->isAdmin()) {
            throw new BusinessException('Forbidden.', 'FORBIDDEN', 403);
        }

        return $user;
    }
}
