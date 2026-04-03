<?php

namespace App\Http\Controllers\Api\V1\Owner;

use App\Contracts\Services\Owner\StaffServiceInterface;
use App\Exceptions\BusinessException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Owner\UpdateStaffScheduleRequest;
use App\Http\Resources\Api\V1\Owner\StaffResource;
use Illuminate\Http\JsonResponse;

class StaffScheduleController extends Controller
{
    public function __construct(
        protected StaffServiceInterface $staffService
    ) {}

    public function update(UpdateStaffScheduleRequest $request, string $id): JsonResponse
    {
        try {
            $staff = $this->staffService->getStaffById($id);
        } catch (BusinessException $e) {
            return $this->error($e->getMessage(), $e->getCode(), ['code' => $e->getErrorCode()]);
        }

        $this->authorize('update', $staff);

        try {
            $updated = $this->staffService->updateSchedule($id, $request->validated(), $request->user());
        } catch (BusinessException $e) {
            return $this->error($e->getMessage(), $e->getCode(), ['code' => $e->getErrorCode()]);
        }

        return $this->success(new StaffResource($updated), 'Cập nhật lịch nhân viên thành công');
    }
}
