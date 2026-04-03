<?php

namespace App\Http\Controllers\Api\V1\Owner;

use App\Contracts\Services\Owner\OwnerSubscriptionServiceInterface;
use App\Http\Controllers\Concerns\HandlesServiceException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Owner\SubmitOwnerSubscriptionPaymentRequest;
use App\Http\Requests\Api\V1\Owner\UpgradeOwnerSubscriptionRequest;
use App\Http\Resources\Api\V1\Owner\OwnerPackagePlanResource;
use App\Http\Resources\Api\V1\Owner\OwnerSubscriptionResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    use HandlesServiceException;

    public function __construct(
        protected OwnerSubscriptionServiceInterface $subscriptionService
    ) {}

    public function show(Request $request): JsonResponse
    {
        return $this->tryService(
            fn () => new OwnerSubscriptionResource($this->subscriptionService->getSubscription($request->user())),
            'Lấy thông tin gói dịch vụ thành công'
        );
    }

    public function plans(Request $request): JsonResponse
    {
        return $this->tryService(
            fn () => OwnerPackagePlanResource::collection($this->subscriptionService->getPlans($request->user())),
            'Lấy danh sách gói nâng cấp thành công'
        );
    }

    public function availablePackages(Request $request): JsonResponse
    {
        return $this->tryService(
            fn () => OwnerPackagePlanResource::collection($this->subscriptionService->getAvailablePackages($request->user())),
            'Lấy danh sách gói dịch vụ thành công'
        );
    }

    public function submitPayment(SubmitOwnerSubscriptionPaymentRequest $request): JsonResponse
    {
        return $this->tryService(
            fn () => $this->subscriptionService->submitPayment($request->user(), $request->validated()),
            'Gửi yêu cầu xác nhận thanh toán thành công'
        );
    }

    public function upgrade(UpgradeOwnerSubscriptionRequest $request): JsonResponse
    {
        return $this->tryService(
            fn () => $this->subscriptionService->upgrade($request->user(), $request->validated()),
            'Gửi yêu cầu nâng cấp gói thành công'
        );
    }
}
