<?php

namespace App\Contracts\Services\Admin;

interface AdminServiceManagementServiceInterface
{
    public function listServices(array $filters): mixed;

    public function createService(array $data): mixed;

    public function updateService(string $id, array $data): mixed;

    public function deleteService(string $id): bool;

    public function setActive(string $id, bool $active): mixed;
}
