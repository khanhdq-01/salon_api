<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Contracts\Services\Admin\AdminPackageManagementServiceInterface;
use App\Http\Controllers\Concerns\HandlesServiceException;
use App\Http\Controllers\Concerns\PaginatesApiResource;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Admin\ListAdminPackagesRequest;
use App\Http\Requests\Api\V1\Admin\StoreAdminPackageRequest;
use App\Http\Requests\Api\V1\Admin\UpdateAdminPackageRequest;
use App\Http\Requests\Shared\RouteIdRequest;
use App\Http\Resources\Api\V1\Admin\AdminPackageResource;
use Illuminate\Http\JsonResponse;

class PackageManagementController extends Controller
{
    use HandlesServiceException, PaginatesApiResource;

    public function __construct(
        protected AdminPackageManagementServiceInterface $packageService
    ) {}

    public function index(ListAdminPackagesRequest $request): JsonResponse
    {
        $paginator = $this->packageService->listPackages($request->validated());

        return $this->paginatedResource($paginator, AdminPackageResource::class, 'Lấy danh sách package thành công');
    }

    public function store(StoreAdminPackageRequest $request): JsonResponse
    {
        return $this->tryService(
            fn () => new AdminPackageResource($this->packageService->createPackage($request->validated())),
            'Tạo package thành công',
        );
    }

    public function update(UpdateAdminPackageRequest $request, string $id): JsonResponse
    {
        return $this->tryService(
            fn () => new AdminPackageResource($this->packageService->updatePackage($id, $request->validated())),
            'Cập nhật package thành công',
        );
    }

    public function destroy(RouteIdRequest $request, string $id): JsonResponse
    {
        return $this->tryService(
            fn () => $this->packageService->deletePackage($id),
            'Xóa package thành công',
        );
    }
}
