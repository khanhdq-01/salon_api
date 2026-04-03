<?php

namespace App\Services\Owner;

use App\Repositories\Interfaces\Owner\SalonRepositoryInterface;
use App\Repositories\Interfaces\Owner\ServiceRepositoryInterface;
use App\Contracts\Services\Owner\OwnerPackageLimitServiceInterface;
use App\Contracts\Services\Owner\ServiceCatalogServiceInterface;
use App\Exceptions\BusinessException;
use App\Models\Service;
use App\Models\User;
use App\Services\Shared\AssertsSalonOwnership;
use App\Services\Owner\ServiceStyleOptionSyncService;
use App\Support\AvailableSlotsCache;
use App\Support\ServiceMapper;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ServiceCatalogService implements ServiceCatalogServiceInterface
{
    use AssertsSalonOwnership;

    public function __construct(
        protected ServiceRepositoryInterface $serviceRepository,
        protected SalonRepositoryInterface $salonRepository,
        protected ServiceStyleOptionSyncService $styleOptionSyncService,
        protected OwnerPackageLimitServiceInterface $packageLimitService,
    ) {}

    public function listServices(array $filters): LengthAwarePaginator
    {
        if (! auth()->check() || auth()->user()?->isCustomer()) {
            $filters['public_only'] = true;
        }

        return $this->serviceRepository->paginate(ServiceMapper::normalizeListFilters($filters));
    }

    public function searchServices(array $filters): LengthAwarePaginator
    {
        $payload = array_merge($filters, ['public_only' => true]);

        return $this->serviceRepository->paginate(ServiceMapper::normalizeListFilters($payload));
    }

    public function createService(array $data, User $actor): Service
    {
        $payload = ServiceMapper::normalizeCreate($data);
        $styleOptions = $data['style_options'] ?? null;
        $salon = $this->findSalonOrFail($this->salonRepository, $payload['salon_id']);
        $this->assertCanManageSalon($salon, $actor);

        if ($actor->isOwner()) {
            $this->packageLimitService->assertCanAddService($actor, $salon->id);
        }

        $service = $this->serviceRepository->create($payload);

        if (is_array($styleOptions)) {
            $this->styleOptionSyncService->sync($service, $styleOptions);
        }

        return $service->fresh(['styleOptions']);
    }

    public function getServiceById(string $id): Service
    {
        return $this->findServiceOrFail($id, ['salon:id,owner_id,name,approval_status,is_locked,status', 'styleOptions']);
    }

    public function updateService(string $id, array $data, User $actor): Service
    {
        $service = $this->findServiceOrFail($id, ['salon', 'styleOptions']);
        $this->assertCanManageSalon($service->salon, $actor);

        $styleOptions = $data['style_options'] ?? null;
        $updated = $this->serviceRepository->update($service, ServiceMapper::normalizeUpdate($data));

        if (is_array($styleOptions)) {
            $this->styleOptionSyncService->sync($updated, $styleOptions);
        }

        AvailableSlotsCache::forgetSalonWide($service->salon_id);

        return $updated->fresh(['styleOptions']);
    }

    public function deleteService(string $id, User $actor): bool
    {
        $service = $this->findServiceOrFail($id, ['salon']);
        $this->assertCanManageSalon($service->salon, $actor);
        $salonId = $service->salon_id;

        $deleted = $this->serviceRepository->delete($service);

        if ($deleted) {
            AvailableSlotsCache::forgetSalonWide($salonId);
        }

        return $deleted;
    }

    protected function findServiceOrFail(string $id, array $relations = []): Service
    {
        $service = $this->serviceRepository->findById($id, $relations);

        if (! $service) {
            throw new BusinessException('Dịch vụ không tồn tại.', 'SERVICE_NOT_FOUND', 404);
        }

        return $service;
    }
}
