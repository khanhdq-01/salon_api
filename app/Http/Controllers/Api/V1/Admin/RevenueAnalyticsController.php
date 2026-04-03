<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Contracts\Services\Admin\AdminRevenueAnalyticsServiceInterface;
use App\Http\Controllers\Concerns\HandlesServiceException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Admin\ListAdminRevenueAnalyticsRequest;
use Illuminate\Http\JsonResponse;

class RevenueAnalyticsController extends Controller
{
    use HandlesServiceException;

    public function __construct(
        protected AdminRevenueAnalyticsServiceInterface $revenueAnalyticsService
    ) {}

    public function index(ListAdminRevenueAnalyticsRequest $request): JsonResponse
    {
        return $this->tryService(
            fn () => $this->revenueAnalyticsService->getAnalytics($request->validated()),
            'Lấy revenue analytics thành công',
        );
    }
}
