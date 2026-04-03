<?php

namespace App\Http\Controllers\Api\V1\Owner;

use App\Contracts\Services\Owner\OwnerDashboardServiceInterface;
use App\Http\Controllers\Concerns\HandlesServiceException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Owner\GetOwnerDashboardRequest;
use App\Http\Resources\Api\V1\Owner\OwnerDashboardResource;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    use HandlesServiceException;

    public function __construct(
        protected OwnerDashboardServiceInterface $dashboardService
    ) {}

    public function __invoke(GetOwnerDashboardRequest $request): JsonResponse
    {
        return $this->tryService(
            fn () => new OwnerDashboardResource($this->dashboardService->getDashboard(
                $request->user(),
                $request->validated(),
            )),
            'Lấy dashboard owner thành công'
        );
    }
}
