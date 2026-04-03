<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Contracts\Services\Admin\AdminUserManagementServiceInterface;
use App\Http\Controllers\Concerns\HandlesServiceException;
use App\Http\Controllers\Concerns\PaginatesApiResource;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Admin\AdminResetPasswordRequest;
use App\Http\Requests\Api\V1\Admin\AdminTransferSalonRequest;
use App\Http\Requests\Api\V1\Admin\AdminUserPasswordRequest;
use App\Http\Requests\Api\V1\Admin\AdminUserProfileRequest;
use App\Http\Requests\Api\V1\Admin\ListAdminUsersRequest;
use App\Http\Requests\Api\V1\Admin\StoreAdminOwnerRequest;
use App\Http\Requests\Api\V1\Admin\UpdateAdminUserRequest;
use App\Http\Requests\Shared\RouteIdRequest;
use App\Http\Resources\Api\V1\Admin\AdminUserResource;
use App\Http\Resources\Api\V1\Customer\SalonResource;
use App\Models\Role;
use Illuminate\Http\JsonResponse;

class OwnerManagementController extends Controller
{
    use HandlesServiceException, PaginatesApiResource;

    public function __construct(
        protected AdminUserManagementServiceInterface $userManagementService
    ) {}

    public function index(ListAdminUsersRequest $request): JsonResponse
    {
        $filters = array_merge($request->validated(), ['role' => Role::OWNER]);
        $paginator = $this->userManagementService->listUsers($filters);

        return $this->paginatedResource($paginator, AdminUserResource::class, 'Lấy danh sách owner thành công');
    }

    public function show(RouteIdRequest $request, string $id): JsonResponse
    {
        return $this->tryService(function () use ($id) {
            $user = $this->userManagementService->getUser($id);
            $this->userManagementService->assertOwner($user);

            return new AdminUserResource($user);
        }, 'Lấy chi tiết owner thành công');
    }

    public function store(StoreAdminOwnerRequest $request): JsonResponse
    {
        return $this->tryService(
            fn () => $this->created(
                new AdminUserResource($this->userManagementService->createUser(array_merge(
                    $request->validated(),
                    ['role' => Role::OWNER]
                ))),
                'Tạo owner thành công'
            )
        );
    }

    public function update(UpdateAdminUserRequest $request, string $id): JsonResponse
    {
        return $this->tryService(function () use ($request, $id) {
            $user = $this->userManagementService->getUser($id);
            $this->userManagementService->assertOwner($user);

            return new AdminUserResource($this->userManagementService->updateUser($id, $request->validated()));
        }, 'Cập nhật owner thành công');
    }

    public function updateProfile(AdminUserProfileRequest $request, string $id): JsonResponse
    {
        return $this->tryService(function () use ($request, $id) {
            $this->userManagementService->assertOwner($this->userManagementService->getUser($id));

            return new AdminUserResource($this->userManagementService->updateProfile($id, $request->validated()));
        }, 'Cập nhật profile owner thành công');
    }

    public function changePassword(AdminUserPasswordRequest $request, string $id): JsonResponse
    {
        return $this->tryService(function () use ($request, $id) {
            $this->userManagementService->assertOwner($this->userManagementService->getUser($id));

            return new AdminUserResource(
                $this->userManagementService->changePassword($id, $request->validated('password'))
            );
        }, 'Đổi mật khẩu owner thành công');
    }

    public function resetPassword(AdminResetPasswordRequest $request, string $id): JsonResponse
    {
        return $this->tryService(function () use ($request, $id) {
            $this->userManagementService->assertOwner($this->userManagementService->getUser($id));

            return new AdminUserResource(
                $this->userManagementService->resetPassword($id, $request->validated('password'))
            );
        }, 'Reset mật khẩu owner thành công');
    }

    public function lock(RouteIdRequest $request, string $id): JsonResponse
    {
        return $this->tryService(function () use ($id) {
            $this->userManagementService->assertOwner($this->userManagementService->getUser($id));

            return new AdminUserResource($this->userManagementService->lockUser($id));
        }, 'Khóa owner thành công');
    }

    public function unlock(RouteIdRequest $request, string $id): JsonResponse
    {
        return $this->tryService(function () use ($id) {
            $this->userManagementService->assertOwner($this->userManagementService->getUser($id));

            return new AdminUserResource($this->userManagementService->unlockUser($id));
        }, 'Mở khóa owner thành công');
    }

    public function destroy(RouteIdRequest $request, string $id): JsonResponse
    {
        return $this->tryService(function () use ($id) {
            $this->userManagementService->assertOwner($this->userManagementService->getUser($id));

            return $this->userManagementService->deleteUser($id);
        }, 'Xóa owner thành công');
    }

    public function transferSalon(AdminTransferSalonRequest $request, string $id): JsonResponse
    {
        return $this->tryService(function () use ($request, $id) {
            $salon = $this->userManagementService->transferOwnerSalon(
                $id,
                $request->validated('salon_id'),
                $request->validated('new_owner_id')
            );

            return new SalonResource($salon);
        }, 'Chuyển quyền salon thành công');
    }
}
