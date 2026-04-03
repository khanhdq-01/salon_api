<?php

namespace App\Repositories\Eloquent\Owner;

use App\Models\ServiceStyleOption;
use App\Repositories\Interfaces\Owner\ServiceStyleOptionRepositoryInterface;
use Illuminate\Support\Collection;

class ServiceStyleOptionRepository implements ServiceStyleOptionRepositoryInterface
{
    public function __construct(
        protected ServiceStyleOption $model
    ) {}

    public function listBySalon(string $salonId): Collection
    {
        return $this->model->newQuery()
            ->whereHas('service', fn ($query) => $query->where('salon_id', $salonId))
            ->with(['service:id,name,salon_id'])
            ->orderBy('sort_order')
            ->orderByDesc('created_at')
            ->get();
    }

    public function findWithService(string $id): ?ServiceStyleOption
    {
        return $this->model->newQuery()
            ->with(['service:id,name,salon_id'])
            ->find($id);
    }

    public function create(array $data): ServiceStyleOption
    {
        return $this->model->newQuery()->create($data);
    }

    public function updateByServiceAndId(string $serviceId, string $id, array $payload): void
    {
        $this->model->newQuery()
            ->where('service_id', $serviceId)
            ->where('id', $id)
            ->update($payload);
    }
}
