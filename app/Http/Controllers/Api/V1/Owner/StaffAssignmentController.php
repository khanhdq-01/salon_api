<?php

namespace App\Http\Controllers\Api\V1\Owner;

use App\Contracts\Services\Owner\StaffServiceInterface;
use App\Exceptions\BusinessException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Owner\AssignStaffServicesRequest;
use App\Http\Resources\Api\V1\Owner\StaffResource;
use Illuminate\Http\JsonResponse;

class StaffAssignmentController extends Controller
{
    public function __construct(
        protected StaffServiceInterface $staffService
    ) {}

    public function assignServices(AssignStaffServicesRequest $request, string $id): JsonResponse
    {
        try {
            $staff = $this->staffService->getStaffById($id);
        } catch (BusinessException $e) {
            return $this->error($e->getMessage(), $e->getCode(), ['code' => $e->getErrorCode()]);
        }

        $this->authorize('update', $staff);

        try {
            $updated = $this->staffService->assignServices(
                $id,
                $request->validated('service_ids'),
                $request->user()
            );
        } catch (BusinessException $e) {
            return $this->error($e->getMessage(), $e->getCode(), ['code' => $e->getErrorCode()]);
        }

        return $this->success(new StaffResource($updated), 'Gán dịch vụ cho nhân viên thành công');
    }
}
