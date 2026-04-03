<?php

namespace App\Services\Admin;

use App\Repositories\Interfaces\Owner\ServiceRepositoryInterface;
use App\Contracts\Services\Admin\AdminServiceManagementServiceInterface;
use App\Contracts\Services\Owner\ServiceCatalogServiceInterface;
use App\Exceptions\BusinessException;
use App\Models\Service;
use App\Models\User;
use App\Support\ServiceMapper;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class AdminServiceManagementService implements AdminServiceManagementServiceInterface
{
    public function __construct(
        protected ServiceCatalogServiceInterface $serviceCatalog,
        protected ServiceRepositoryInterface $serviceRepository,
    ) {}

    public function listServices(array $filters): LengthAwarePaginator
    {
        $filters['public_only'] = false;

        return $this->serviceCatalog->listServices($filters);
    }

    public function createService(array $data): Service
    {
        return $this->serviceCatalog->createService($data, $this->adminUser());
    }

    public function updateService(string $id, array $data): Service
    {
        return $this->serviceCatalog->updateService($id, $data, $this->adminUser());
    }

    public function deleteService(string $id): bool
    {
        return $this->serviceCatalog->deleteService($id, $this->adminUser());
    }

    public function setActive(string $id, bool $active): Service
    {
        $service = $this->serviceRepository->findById($id);
        if (! $service) {
            throw new BusinessException('Dịch vụ không tồn tại.', 'SERVICE_NOT_FOUND', 404);
        }

        return $this->serviceCatalog->updateService($id, ['is_active' => $active], $this->adminUser());
    }

    protected function adminUser(): User
    {
        $user = Auth::user();

        if (! $user instanceof User || ! $user->isAdmin()) {
            throw new BusinessException('Forbidden.', 'FORBIDDEN', 403);
        }

        return $user;
    }
}
