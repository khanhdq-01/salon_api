<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Contracts\Services\Admin\AdminDashboardServiceInterface;
use App\Http\Controllers\Concerns\HandlesServiceException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Admin\GetAdminDashboardRequest;
use App\Http\Resources\Api\V1\Admin\AdminDashboardResource;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    use HandlesServiceException;

    public function __construct(
        protected AdminDashboardServiceInterface $dashboardService
    ) {}

    public function __invoke(GetAdminDashboardRequest $request): JsonResponse
    {
        return $this->tryService(
            fn () => new AdminDashboardResource($this->dashboardService->getDashboard($request->validated())),
            'Lấy dashboard admin thành công'
        );
    }
}
