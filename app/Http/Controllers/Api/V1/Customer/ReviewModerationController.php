<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Contracts\Services\Customer\ReviewServiceInterface;
use App\Exceptions\BusinessException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Customer\ReportReviewRequest;
use App\Http\Resources\Api\V1\Customer\ReviewResource;
use App\Models\Review;
use App\Repositories\Interfaces\Customer\ReviewRepositoryInterface;
use Illuminate\Http\JsonResponse;

class ReviewModerationController extends Controller
{
    public function __construct(
        protected ReviewServiceInterface $reviewService,
        protected ReviewRepositoryInterface $reviewRepository,
    ) {}

    public function report(ReportReviewRequest $request, string $id): JsonResponse
    {
        $review = $this->reviewRepository->findById($id);

        if (! $review) {
            return $this->notFound('Đánh giá không tồn tại');
        }

        $this->authorize('report', $review);

        try {
            $result = $this->reviewService->reportReview($id, $request->validated(), $request->user());
        } catch (BusinessException $e) {
            return $this->error($e->getMessage(), $e->getCode(), ['code' => $e->getErrorCode()]);
        }

        return $this->success(new ReviewResource($result), 'Báo cáo đánh giá thành công');
    }
}
