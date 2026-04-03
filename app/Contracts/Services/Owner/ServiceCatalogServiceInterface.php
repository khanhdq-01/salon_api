<?php

namespace App\Contracts\Services\Owner;

interface ServiceCatalogServiceInterface
{
    public function listServices(array $filters): mixed;

    public function searchServices(array $filters): mixed;

    public function createService(array $data, \App\Models\User $actor): mixed;

    public function getServiceById(string $id): mixed;

    public function updateService(string $id, array $data, \App\Models\User $actor): mixed;

    public function deleteService(string $id, \App\Models\User $actor): bool;
}
