<?php

namespace App\Contracts\Services\Admin;

interface AdminSalonManagementServiceInterface
{
    public function listSalons(array $filters): mixed;

    public function getSalon(string $id): mixed;

    public function createSalon(array $data): mixed;

    public function updateSalon(string $id, array $data): mixed;

    public function updateProfile(string $id, array $data): mixed;

    public function approveSalon(string $id): mixed;

    public function rejectSalon(string $id): mixed;

    public function lockSalon(string $id): mixed;

    public function unlockSalon(string $id): mixed;

    public function activateSalon(string $id): mixed;

    public function deactivateSalon(string $id): mixed;

    public function changeOwner(string $id, string $ownerId): mixed;

    public function deleteSalon(string $id): bool;
    public function restoreSalon(string $id): mixed;
}
