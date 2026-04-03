<?php

namespace App\Http\Controllers\Api\V1\Owner;

use App\Contracts\Services\Owner\StaffServiceInterface;
use App\Exceptions\BusinessException;
use App\Http\Controllers\Concerns\PaginatesApiResource;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Owner\AssignStaffServicesRequest;
use App\Http\Requests\Api\V1\Owner\ListStaffRequest;
use App\Http\Requests\Api\V1\Owner\StoreStaffRequest;
use App\Http\Requests\Api\V1\Owner\UpdateStaffRequest;
use App\Http\Requests\Api\V1\Owner\UpdateStaffScheduleRequest;
use App\Http\Resources\Api\V1\Owner\StaffListResource;
use App\Http\Resources\Api\V1\Owner\StaffResource;
use App\Models\Staff;
use Illuminate\Http\JsonResponse;

class StaffController extends Controller
{
    use PaginatesApiResource;

    public function __construct(
        protected StaffServiceInterface $staffService
    ) {}

    public function index(ListStaffRequest $request): JsonResponse
    {
        $this->authorize('viewAny', Staff::class);

        $paginator = $this->staffService->listStaff($request->validated(), $request->user());

        return $this->paginatedResource($paginator, StaffListResource::class, 'Lấy danh sách nhân viên thành công');
    }

    public function store(StoreStaffRequest $request): JsonResponse
    {
        $this->authorize('create', Staff::class);

        try {
            $staff = $this->staffService->createStaff($request->validated(), $request->user());
        } catch (BusinessException $e) {
            return $this->error($e->getMessage(), $e->getCode(), ['code' => $e->getErrorCode()]);
        }

        return $this->created(new StaffResource($staff), 'Tạo nhân viên thành công');
    }

    public function show(string $id): JsonResponse
    {
        try {
            $staff = $this->staffService->getStaffById($id);
        } catch (BusinessException $e) {
            return $this->error($e->getMessage(), $e->getCode(), ['code' => $e->getErrorCode()]);
        }

        $this->authorize('view', $staff);

        return $this->success(new StaffResource($staff), 'Lấy chi tiết nhân viên thành công');
    }

    public function update(UpdateStaffRequest $request, string $id): JsonResponse
    {
        try {
            $staff = $this->staffService->getStaffById($id);
        } catch (BusinessException $e) {
            return $this->error($e->getMessage(), $e->getCode(), ['code' => $e->getErrorCode()]);
        }

        $this->authorize('update', $staff);

        try {
            $updated = $this->staffService->updateStaff($id, $request->validated(), $request->user());
        } catch (BusinessException $e) {
            return $this->error($e->getMessage(), $e->getCode(), ['code' => $e->getErrorCode()]);
        }

        return $this->success(new StaffResource($updated), 'Cập nhật nhân viên thành công');
    }

    public function destroy(string $id): JsonResponse
    {
        try {
            $staff = $this->staffService->getStaffById($id);
        } catch (BusinessException $e) {
            return $this->error($e->getMessage(), $e->getCode(), ['code' => $e->getErrorCode()]);
        }

        $this->authorize('delete', $staff);

        try {
            $this->staffService->deleteStaff($id, auth()->user());
        } catch (BusinessException $e) {
            return $this->error($e->getMessage(), $e->getCode(), ['code' => $e->getErrorCode()]);
        }

        return $this->noContent('Xóa nhân viên thành công');
    }
}
