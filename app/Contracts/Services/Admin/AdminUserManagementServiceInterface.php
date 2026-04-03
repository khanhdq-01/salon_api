<?php

namespace App\Contracts\Services\Admin;

interface AdminUserManagementServiceInterface
{
    public function listUsers(array $filters): mixed;

    public function getUser(string $id): mixed;

    public function createUser(array $data): mixed;

    public function updateUser(string $id, array $data): mixed;

    public function updateProfile(string $id, array $data): mixed;

    public function changePassword(string $id, string $password): mixed;

    public function resetPassword(string $id, string $password): mixed;

    public function changeRole(string $id, string $roleName): mixed;

    public function lockUser(string $id): mixed;

    public function unlockUser(string $id): mixed;

    public function deleteUser(string $id): bool;

    public function transferOwnerSalon(string $ownerId, string $salonId, string $newOwnerId): mixed;

    public function assertOwner(\App\Models\User $user): void;
}
