<?php

namespace App\Http\Controllers\Api\V1\Owner;

use App\Contracts\Services\Owner\OwnerReportServiceInterface;
use App\Http\Controllers\Concerns\HandlesServiceException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Owner\GetOwnerReportRequest;
use App\Http\Resources\Api\V1\Owner\OwnerReportResource;
use Illuminate\Http\JsonResponse;

class ReportController extends Controller
{
    use HandlesServiceException;

    public function __construct(
        protected OwnerReportServiceInterface $reportService
    ) {}

    public function __invoke(GetOwnerReportRequest $request): JsonResponse
    {
        return $this->tryService(
            fn () => new OwnerReportResource($this->reportService->getReport($request->user(), $request->validated())),
            'Lấy báo cáo owner thành công'
        );
    }
}
