<?php

namespace App\Services\Customer;

use App\Repositories\Interfaces\Customer\BookingRepositoryInterface;
use App\Repositories\Interfaces\Customer\ReviewRepositoryInterface;
use App\Repositories\Interfaces\Owner\SalonRepositoryInterface;
use App\Contracts\Services\Customer\ReviewServiceInterface;
use App\Exceptions\BusinessException;
use App\Models\Booking;
use App\Models\Review;
use App\Models\User;
use App\Support\ReviewMapper;
use App\Support\SalonVisibility;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ReviewService implements ReviewServiceInterface
{
    public function __construct(
        protected ReviewRepositoryInterface $reviewRepository,
        protected BookingRepositoryInterface $bookingRepository,
        protected SalonRepositoryInterface $salonRepository,
    ) {}

    public function listSalonReviews(string $salonId, array $filters, ?User $actor = null): LengthAwarePaginator
    {
        $salon = $this->salonRepository->findById($salonId, ['owner:id,status']);

        if (! $salon) {
            throw new BusinessException('Salon không tồn tại.', 'SALON_NOT_FOUND', 404);
        }

        SalonVisibility::assertCustomerAccessible($salon, $actor);

        return $this->reviewRepository->paginateBySalon(
            $salonId,
            ReviewMapper::normalizeListFilters($filters)
        );
    }

    public function getSalonReviewSummary(string $salonId): array
    {
        $stats = $this->reviewRepository->getSalonRatingStats($salonId);
        $distribution = $this->reviewRepository->getSalonRatingDistribution($salonId);

        return [
            'rating_avg' => $stats['avg'],
            'rating_count' => $stats['count'],
            'rating_distribution' => $distribution,
        ];
    }

    public function getCustomerReviewContext(string $salonId, ?User $actor): array
    {
        if (! $actor || ! $actor->isCustomer()) {
            return [
                'can_review' => false,
                'reviewable_booking_id' => null,
            ];
        }

        $booking = $this->bookingRepository->findReviewableForSalon($salonId, $actor->id);

        return [
            'can_review' => $booking !== null,
            'reviewable_booking_id' => $booking?->id,
        ];
    }

    public function createSalonReview(string $salonId, array $data, User $actor): Review
    {
        $salon = $this->salonRepository->findById($salonId);

        if (! $salon) {
            throw new BusinessException('Salon không tồn tại.', 'SALON_NOT_FOUND', 404);
        }

        $bookingId = $data['booking_id'] ?? null;
        $booking = $this->bookingRepository->findReviewableForSalon($salonId, $actor->id, $bookingId);

        if (! $booking) {
            throw new BusinessException(
                'Bạn cần hoàn thành đặt lịch tại salon này trước khi đánh giá.',
                'REVIEW_NOT_ELIGIBLE',
                403
            );
        }

        unset($data['booking_id']);

        return $this->createReview($booking->id, $data, $actor);
    }

    public function createReview(string $bookingId, array $data, User $actor): Review
    {
        $booking = $this->bookingRepository->findById($bookingId, ['salon']);

        if (! $booking) {
            throw new BusinessException('Booking không tồn tại.', 'BOOKING_NOT_FOUND', 404);
        }

        if ($booking->customer_id !== $actor->id) {
            throw new BusinessException('Không có quyền đánh giá booking này.', 'FORBIDDEN', 403);
        }

        if ($booking->status !== Booking::STATUS_COMPLETED) {
            throw new BusinessException('Chỉ đánh giá được booking đã hoàn thành.', 'BOOKING_NOT_COMPLETED');
        }

        if ($booking->has_reviewed || $this->reviewRepository->findByBookingId($bookingId)) {
            throw new BusinessException('Booking đã được đánh giá.', 'REVIEW_ALREADY_EXISTS', 409);
        }

        $payload = ReviewMapper::normalizeCreate($data);

        if ($payload['comment'] === '') {
            throw new BusinessException('Nội dung đánh giá không được để trống.', 'COMMENT_REQUIRED');
        }

        return DB::transaction(function () use ($booking, $payload, $actor) {
            $review = $this->reviewRepository->create([
                'booking_id' => $booking->id,
                'salon_id' => $booking->salon_id,
                'customer_id' => $actor->id,
                'rating' => $payload['rating'],
                'comment' => $payload['comment'],
            ]);

            $this->bookingRepository->update($booking, ['has_reviewed' => true]);
            $this->syncSalonRating($booking->salon_id);

            return $review->load(['customer:id,name,avatar_url']);
        });
    }

    public function updateReview(string $id, array $data, User $actor): Review
    {
        $review = $this->findReviewOrFail($id);

        if ($review->customer_id !== $actor->id && ! $actor->isAdmin()) {
            throw new BusinessException('Không có quyền sửa đánh giá này.', 'FORBIDDEN', 403);
        }

        $payload = ReviewMapper::normalizeUpdate($data);

        if (isset($payload['comment']) && $payload['comment'] === '') {
            throw new BusinessException('Nội dung đánh giá không được để trống.', 'COMMENT_REQUIRED');
        }

        return DB::transaction(function () use ($review, $payload) {
            $updated = $this->reviewRepository->update($review, $payload);
            $this->syncSalonRating($review->salon_id);

            return $updated;
        });
    }

    public function deleteReview(string $id, User $actor): bool
    {
        $review = $this->findReviewOrFail($id, ['booking']);

        if ($review->customer_id !== $actor->id && ! $actor->isAdmin()) {
            throw new BusinessException('Không có quyền xóa đánh giá này.', 'FORBIDDEN', 403);
        }

        return DB::transaction(function () use ($review) {
            if ($review->booking) {
                $this->bookingRepository->update($review->booking, ['has_reviewed' => false]);
            }

            $salonId = $review->salon_id;
            $deleted = $this->reviewRepository->delete($review);
            $this->syncSalonRating($salonId);

            return $deleted;
        });
    }

    public function reportReview(string $id, array $data, User $actor): Review
    {
        $review = $this->findReviewOrFail($id);
        $payload = ReviewMapper::normalizeReport($data);

        if ($payload['reason'] === '') {
            throw new BusinessException('Lý do báo cáo không được để trống.', 'REASON_REQUIRED');
        }

        $this->reviewRepository->createReport($review, $payload, $actor->id);

        return $review->load(['customer:id,name,avatar_url']);
    }

    protected function findReviewOrFail(string $id, array $relations = []): Review
    {
        $review = $this->reviewRepository->findById($id, $relations);

        if (! $review) {
            throw new BusinessException('Đánh giá không tồn tại.', 'REVIEW_NOT_FOUND', 404);
        }

        return $review;
    }

    protected function syncSalonRating(string $salonId): void
    {
        $stats = $this->reviewRepository->getSalonRatingStats($salonId);
        $this->reviewRepository->updateSalonRating($salonId, $stats['avg'], $stats['count']);
    }
}
