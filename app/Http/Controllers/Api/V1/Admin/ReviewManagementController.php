<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Contracts\Services\Admin\AdminReviewManagementServiceInterface;
use App\Http\Controllers\Concerns\HandlesServiceException;
use App\Http\Controllers\Concerns\PaginatesApiResource;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Admin\ListAdminReviewReportsRequest;
use App\Http\Requests\Api\V1\Admin\ListAdminReviewsRequest;
use App\Http\Requests\Shared\RouteIdRequest;
use App\Http\Resources\Api\V1\Customer\ReviewResource;
use Illuminate\Http\JsonResponse;

class ReviewManagementController extends Controller
{
    use HandlesServiceException, PaginatesApiResource;

    public function __construct(
        protected AdminReviewManagementServiceInterface $reviewManagement
    ) {}

    public function index(ListAdminReviewsRequest $request): JsonResponse
    {
        $paginator = $this->reviewManagement->listReviews($request->validated());

        return $this->paginatedResource($paginator, ReviewResource::class, 'Lấy danh sách review thành công');
    }

    public function show(RouteIdRequest $request, string $id): JsonResponse
    {
        return $this->tryService(
            fn () => new ReviewResource($this->reviewManagement->getReview($id)),
            'Lấy chi tiết review thành công'
        );
    }

    public function hide(RouteIdRequest $request, string $id): JsonResponse
    {
        return $this->tryService(
            fn () => $this->reviewManagement->hideReview($id),
            'Ẩn review thành công'
        );
    }

    public function showReview(RouteIdRequest $request, string $id): JsonResponse
    {
        return $this->tryService(
            fn () => new ReviewResource($this->reviewManagement->showReview($id)),
            'Hiện review thành công'
        );
    }

    public function destroy(RouteIdRequest $request, string $id): JsonResponse
    {
        return $this->tryService(
            fn () => $this->reviewManagement->deleteReview($id),
            'Xóa review thành công'
        );
    }

    public function reports(ListAdminReviewReportsRequest $request): JsonResponse
    {
        $paginator = $this->reviewManagement->listReports($request->validated());

        return $this->paginatedResource($paginator, ReviewResource::class, 'Lấy báo cáo review thành công');
    }

    public function resolveReport(RouteIdRequest $request, string $id): JsonResponse
    {
        return $this->tryService(
            fn () => $this->reviewManagement->resolveReport($id),
            'Xử lý báo cáo thành công'
        );
    }
}
