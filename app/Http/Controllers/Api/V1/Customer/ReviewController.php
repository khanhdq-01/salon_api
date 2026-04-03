<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Contracts\Services\Customer\ReviewServiceInterface;
use App\Exceptions\BusinessException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Customer\ListSalonReviewsRequest;
use App\Http\Requests\Api\V1\Customer\StoreReviewRequest;
use App\Http\Requests\Api\V1\Customer\StoreSalonReviewRequest;
use App\Http\Requests\Api\V1\Customer\UpdateReviewRequest;
use App\Http\Requests\Shared\RouteIdRequest;
use App\Http\Resources\Api\V1\Customer\ReviewResource;
use App\Models\Review;
use App\Repositories\Interfaces\Customer\ReviewRepositoryInterface;
use Illuminate\Http\JsonResponse;

class ReviewController extends Controller
{
    public function __construct(
        protected ReviewServiceInterface $reviewService,
        protected ReviewRepositoryInterface $reviewRepository,
    ) {}

    public function index(ListSalonReviewsRequest $request, string $salonId): JsonResponse
    {
        $this->authorize('viewAny', Review::class);

        try {
            $paginator = $this->reviewService->listSalonReviews($salonId, $request->validated(), $request->user());
            $summary = $this->reviewService->getSalonReviewSummary($salonId);
            $reviewContext = $this->reviewService->getCustomerReviewContext($salonId, $request->user());
        } catch (BusinessException $e) {
            return $this->error($e->getMessage(), $e->getCode(), ['code' => $e->getErrorCode()]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Lấy danh sách đánh giá thành công',
            'data' => ReviewResource::collection($paginator->items()),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'summary' => $summary,
                'can_review' => $reviewContext['can_review'],
                'reviewable_booking_id' => $reviewContext['reviewable_booking_id'],
            ],
        ]);
    }

    public function storeForSalon(StoreSalonReviewRequest $request, string $salonId): JsonResponse
    {
        $this->authorize('create', Review::class);

        try {
            $review = $this->reviewService->createSalonReview($salonId, $request->validated(), $request->user());
        } catch (BusinessException $e) {
            return $this->error($e->getMessage(), $e->getCode(), ['code' => $e->getErrorCode()]);
        }

        return $this->created(new ReviewResource($review), 'Gửi đánh giá thành công');
    }

    public function store(StoreReviewRequest $request, string $bookingId): JsonResponse
    {
        $this->authorize('create', Review::class);

        try {
            $review = $this->reviewService->createReview($bookingId, $request->validated(), $request->user());
        } catch (BusinessException $e) {
            return $this->error($e->getMessage(), $e->getCode(), ['code' => $e->getErrorCode()]);
        }

        return $this->created(new ReviewResource($review), 'Gửi đánh giá thành công');
    }

    public function update(UpdateReviewRequest $request, string $id): JsonResponse
    {
        $reviewModel = $this->reviewRepository->findById($id);

        if (! $reviewModel) {
            return $this->notFound('Đánh giá không tồn tại');
        }

        $this->authorize('update', $reviewModel);

        try {
            $review = $this->reviewService->updateReview($id, $request->validated(), $request->user());
        } catch (BusinessException $e) {
            return $this->error($e->getMessage(), $e->getCode(), ['code' => $e->getErrorCode()]);
        }

        return $this->success(new ReviewResource($review), 'Cập nhật đánh giá thành công');
    }

    public function destroy(RouteIdRequest $request, string $id): JsonResponse
    {
        $reviewModel = $this->reviewRepository->findById($id);

        if (! $reviewModel) {
            return $this->notFound('Đánh giá không tồn tại');
        }

        $this->authorize('delete', $reviewModel);

        try {
            $this->reviewService->deleteReview($id, $request->user());
        } catch (BusinessException $e) {
            return $this->error($e->getMessage(), $e->getCode(), ['code' => $e->getErrorCode()]);
        }

        return $this->noContent('Xóa đánh giá thành công');
    }
}
