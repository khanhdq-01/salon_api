<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Contracts\Services\Admin\AdminStaffManagementServiceInterface;
use App\Http\Controllers\Concerns\HandlesServiceException;
use App\Http\Controllers\Concerns\PaginatesApiResource;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Admin\AdminChangeStaffSalonRequest;
use App\Http\Requests\Api\V1\Admin\ListAdminStaffRequest;
use App\Http\Requests\Api\V1\Admin\StoreAdminStaffRequest;
use App\Http\Requests\Api\V1\Admin\UpdateAdminStaffRequest;
use App\Http\Requests\Shared\RouteIdRequest;
use App\Http\Resources\Api\V1\Owner\StaffResource;
use Illuminate\Http\JsonResponse;

class StaffManagementController extends Controller
{
    use HandlesServiceException, PaginatesApiResource;

    public function __construct(
        protected AdminStaffManagementServiceInterface $staffManagement
    ) {}

    public function index(ListAdminStaffRequest $request): JsonResponse
    {
        $paginator = $this->staffManagement->listStaff($request->validated());

        return $this->paginatedResource($paginator, StaffResource::class, 'Lấy danh sách staff thành công');
    }

    public function store(StoreAdminStaffRequest $request): JsonResponse
    {
        return $this->tryService(
            fn () => $this->created(
                new StaffResource($this->staffManagement->createStaff($request->validated())),
                'Tạo staff thành công'
            )
        );
    }

    public function update(UpdateAdminStaffRequest $request, string $id): JsonResponse
    {
        return $this->tryService(
            fn () => new StaffResource($this->staffManagement->updateStaff($id, $request->validated())),
            'Cập nhật staff thành công'
        );
    }

    public function destroy(RouteIdRequest $request, string $id): JsonResponse
    {
        return $this->tryService(
            fn () => $this->staffManagement->deleteStaff($id),
            'Xóa staff thành công'
        );
    }

    public function activate(RouteIdRequest $request, string $id): JsonResponse
    {
        return $this->tryService(
            fn () => new StaffResource($this->staffManagement->setActive($id, true)),
            'Kích hoạt staff thành công'
        );
    }

    public function deactivate(RouteIdRequest $request, string $id): JsonResponse
    {
        return $this->tryService(
            fn () => new StaffResource($this->staffManagement->setActive($id, false)),
            'Vô hiệu hóa staff thành công'
        );
    }

    public function changeSalon(AdminChangeStaffSalonRequest $request, string $id): JsonResponse
    {
        return $this->tryService(
            fn () => new StaffResource(
                $this->staffManagement->changeSalon($id, $request->validated('salon_id'))
            ),
            'Đổi salon staff thành công'
        );
    }
}
