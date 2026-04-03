<?php

namespace App\Services\Admin;

use App\Contracts\Services\Admin\AdminUserManagementServiceInterface;
use App\Exceptions\BusinessException;
use App\Models\Package;
use App\Models\Role;
use App\Models\Salon;
use App\Models\Subscription;
use App\Models\User;
use App\Repositories\Interfaces\Admin\PackageRepositoryInterface;
use App\Repositories\Interfaces\Admin\RoleRepositoryInterface;
use App\Repositories\Interfaces\Admin\SalonRepositoryInterface;
use App\Repositories\Interfaces\Admin\SubscriptionRepositoryInterface;
use App\Repositories\Interfaces\Admin\UserRepositoryInterface as AdminUserRepositoryInterface;
use App\Repositories\Interfaces\Customer\UserRepositoryInterface as CustomerUserRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AdminUserManagementService implements AdminUserManagementServiceInterface
{
    public function __construct(
        protected CustomerUserRepositoryInterface $customerUserRepository,
        protected AdminUserRepositoryInterface $userRepository,
        protected SalonRepositoryInterface $salonRepository,
        protected RoleRepositoryInterface $roleRepository,
        protected SubscriptionRepositoryInterface $subscriptionRepository,
        protected PackageRepositoryInterface $packageRepository,
    ) {}

    public function listUsers(array $filters): LengthAwarePaginator
    {
        return $this->userRepository->paginate(
            $filters,
            array_merge(
                ['role:id,name,display_name'],
                $this->ownerSalonsRelation(),
                $this->ownerSubscriptionsRelation(),
            ),
            ['bookingsAsCustomer']
        );
    }

    public function getUser(string $id): User
    {
        return $this->loadUser($this->findUserOrFail($id));
    }

    public function createUser(array $data): User
    {
        $this->assertAdmin();

        $roleName = $data['role'] ?? Role::CUSTOMER;
        if ($roleName === Role::ADMIN) {
            throw new BusinessException('Không thể tạo tài khoản admin qua API.', 'CANNOT_CREATE_ADMIN', 422);
        }

        $roleId = $this->roleRepository->findIdByName($roleName);
        if (! $roleId) {
            throw new BusinessException('Role không hợp lệ.', 'INVALID_ROLE', 422);
        }

        if ($this->customerUserRepository->findByEmail($data['email'])) {
            throw new BusinessException('Email đã tồn tại.', 'EMAIL_EXISTS', 422);
        }

        $user = DB::transaction(function () use ($data, $roleId, $roleName) {
            $user = $this->customerUserRepository->create([
                'id' => Str::uuid(),
                'role_id' => $roleId,
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'phone' => $data['phone'] ?? null,
                'address' => $data['address'] ?? null,
                'status' => $data['status'] ?? User::STATUS_ACTIVE,
                'token_version' => 0,
            ]);

            return $user;
        });

        return $this->loadUser($user);
    }

    public function updateUser(string $id, array $data): User
    {
        $user = $this->findUserOrFail($id);
        $this->assertCanManage($user);

        $user = $this->userRepository->update($user, array_filter([
            'name' => $data['name'] ?? null,
            'email' => $data['email'] ?? null,
            'phone' => array_key_exists('phone', $data) ? $data['phone'] : null,
            'address' => array_key_exists('address', $data) ? $data['address'] : null,
            'status' => $data['status'] ?? null,
        ], fn ($v) => $v !== null));

        return $this->loadUser($user);
    }

    public function updateProfile(string $id, array $data): User
    {
        return $this->updateUser($id, $data);
    }

    public function changePassword(string $id, string $password): User
    {
        $user = $this->findUserOrFail($id);
        $this->assertCanManage($user);

        $this->customerUserRepository->updatePassword($user, $password);

        return $this->loadUser($user->fresh());
    }

    public function resetPassword(string $id, string $password): User
    {
        $user = $this->findUserOrFail($id);
        $this->assertCanManage($user);

        $this->customerUserRepository->updatePassword($user, $password);
        $user->token_version = $user->token_version + 1;
        $user->save();

        return $this->loadUser($user->fresh());
    }

    public function changeRole(string $id, string $roleName): User
    {
        $user = $this->findUserOrFail($id);
        $this->assertCanManage($user);

        if (! in_array($roleName, [Role::CUSTOMER, Role::OWNER], true)) {
            throw new BusinessException('Chỉ được đổi role customer hoặc owner.', 'INVALID_ROLE', 422);
        }

        $roleId = $this->roleRepository->findIdByName($roleName);
        $user = $this->userRepository->update($user, [
            'role_id' => $roleId,
            'token_version' => $user->token_version + 1,
        ]);

        return $this->loadUser($user);
    }

    public function lockUser(string $id): User
    {
        $user = $this->findUserOrFail($id);
        $this->assertCanManage($user);

        $user = $this->userRepository->update($user, [
            'status' => User::STATUS_SUSPENDED,
            'token_version' => $user->token_version + 1,
        ]);

        if ($user->isOwner()) {
            $this->salonRepository->lockByOwnerId($user->id);
            Cache::increment('salons:list:version');
        }

        return $this->loadUser($user);
    }

    public function unlockUser(string $id): User
    {
        $user = $this->findUserOrFail($id);
        $this->assertCanManage($user);

        $user = $this->userRepository->update($user, ['status' => User::STATUS_ACTIVE]);

        if ($user->isOwner()) {
            $this->salonRepository->unlockByOwnerId($user->id);
            Cache::increment('salons:list:version');
        }

        return $this->loadUser($user);
    }

    public function deleteUser(string $id): bool
    {
        $user = $this->findUserOrFail($id);
        $this->assertCanManage($user);

        return $this->userRepository->delete($user);
    }

    public function transferOwnerSalon(string $ownerId, string $salonId, string $newOwnerId): Salon
    {
        $this->assertAdmin();

        $salon = $this->salonRepository->findById($salonId);
        if (! $salon) {
            throw new BusinessException('Salon không tồn tại.', 'SALON_NOT_FOUND', 404);
        }

        if ($salon->owner_id !== $ownerId) {
            throw new BusinessException('Salon không thuộc owner này.', 'SALON_OWNER_MISMATCH', 422);
        }

        $newOwner = $this->findUserOrFail($newOwnerId);
        if (! $newOwner->isOwner()) {
            throw new BusinessException('Người nhận phải là owner.', 'INVALID_NEW_OWNER', 422);
        }

        if ($this->salonRepository->newOwnerHasOtherSalon($newOwnerId, $salonId)) {
            throw new BusinessException('Owner mới đã có salon. MVP chỉ hỗ trợ 1 salon/owner.', 'NEW_OWNER_HAS_SALON', 422);
        }

        return DB::transaction(function () use ($salon, $newOwnerId) {
            return $this->salonRepository->update($salon, ['owner_id' => $newOwnerId]);
        });
    }

    public function assertOwner(User $user): void
    {
        if (! $user->isOwner()) {
            throw new BusinessException('User không phải owner.', 'NOT_OWNER', 422);
        }
    }

    protected function loadUser(User $user): User
    {
        return $user->load(array_merge(
            ['role:id,name,display_name'],
            $this->ownerSalonsRelation(),
            $this->ownerSubscriptionsRelation(),
        ))->loadCount('bookingsAsCustomer');
    }

    protected function ownerSalonsRelation(): array
    {
        return [
            'ownedSalons' => fn ($query) => $query
                ->withTrashed()
                ->select('id', 'owner_id', 'name', 'approval_status', 'is_locked', 'deleted_at'),
        ];
    }

    protected function ownerSubscriptionsRelation(): array
    {
        return [
            'subscriptions' => fn ($query) => $query
                ->with('package:id,name')
                ->orderByRaw("CASE WHEN status = '".Subscription::STATUS_ACTIVE."' THEN 0 ELSE 1 END")
                ->orderByDesc('end_date'),
        ];
    }

    protected function findActivePackage(?string $packageId): Package
    {
        if (! $packageId) {
            throw new BusinessException('Vui lòng chọn gói dịch vụ.', 'PACKAGE_REQUIRED', 422);
        }

        $package = $this->packageRepository->findActiveById($packageId);

        if (! $package) {
            throw new BusinessException('Gói dịch vụ không khả dụng.', 'PACKAGE_NOT_FOUND', 422);
        }

        return $package;
    }

    protected function findUserOrFail(string $id): User
    {
        $user = $this->userRepository->findById($id);

        if (! $user) {
            throw new BusinessException('User không tồn tại.', 'USER_NOT_FOUND', 404);
        }

        return $user;
    }

    protected function assertCanManage(User $user): void
    {
        $this->assertAdmin();

        if ($user->id === Auth::id()) {
            throw new BusinessException('Không thể thao tác trên chính tài khoản admin.', 'CANNOT_MANAGE_SELF', 422);
        }

        if ($user->isAdmin()) {
            throw new BusinessException('Không thể thao tác trên tài khoản admin khác.', 'CANNOT_MANAGE_ADMIN', 422);
        }
    }

    protected function assertAdmin(): void
    {
        /** @var User|null $admin */
        $admin = Auth::user();

        if (! $admin?->isAdmin()) {
            throw new BusinessException('Forbidden.', 'FORBIDDEN', 403);
        }
    }
}
