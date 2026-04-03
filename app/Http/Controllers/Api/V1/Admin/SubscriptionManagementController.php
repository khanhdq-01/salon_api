<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Contracts\Services\Admin\AdminSubscriptionManagementServiceInterface;
use App\Http\Controllers\Concerns\HandlesServiceException;
use App\Http\Controllers\Concerns\PaginatesApiResource;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Admin\ListAdminSubscriptionsRequest;
use App\Http\Requests\Api\V1\Admin\StoreAdminSubscriptionRequest;
use App\Http\Requests\Api\V1\Admin\UpdateAdminSubscriptionRequest;
use App\Http\Requests\Shared\RouteIdRequest;
use App\Http\Resources\Api\V1\Admin\AdminSubscriptionResource;
use Illuminate\Http\JsonResponse;

class SubscriptionManagementController extends Controller
{
    use HandlesServiceException, PaginatesApiResource;

    public function __construct(
        protected AdminSubscriptionManagementServiceInterface $subscriptionService
    ) {}

    public function index(ListAdminSubscriptionsRequest $request): JsonResponse
    {
        $paginator = $this->subscriptionService->listSubscriptions($request->validated());

        return $this->paginatedResource($paginator, AdminSubscriptionResource::class, 'Lấy danh sách subscription thành công');
    }

    public function store(StoreAdminSubscriptionRequest $request): JsonResponse
    {
        return $this->tryService(
            fn () => new AdminSubscriptionResource($this->subscriptionService->createSubscription($request->validated())),
            'Tạo subscription thành công',
        );
    }

    public function show(RouteIdRequest $request, string $id): JsonResponse
    {
        return $this->tryService(
            fn () => new AdminSubscriptionResource($this->subscriptionService->getSubscription($id)),
            'Lấy chi tiết subscription thành công',
        );
    }

    public function update(UpdateAdminSubscriptionRequest $request, string $id): JsonResponse
    {
        return $this->tryService(
            fn () => new AdminSubscriptionResource($this->subscriptionService->updateSubscription($id, $request->validated())),
            'Cập nhật subscription thành công',
        );
    }

    public function destroy(RouteIdRequest $request, string $id): JsonResponse
    {
        return $this->tryService(
            fn () => $this->subscriptionService->deleteSubscription($id),
            'Xóa subscription thành công',
        );
    }

    public function approve(RouteIdRequest $request, string $id): JsonResponse
    {
        return $this->tryService(
            fn () => new AdminSubscriptionResource(
                $this->subscriptionService->approveUpgrade($id, $request->user())
            ),
            'Duyệt nâng cấp gói thành công',
        );
    }

    public function reject(RouteIdRequest $request, string $id): JsonResponse
    {
        return $this->tryService(
            fn () => new AdminSubscriptionResource(
                $this->subscriptionService->rejectUpgrade($id, $request->user())
            ),
            'Từ chối yêu cầu nâng cấp thành công',
        );
    }
}
