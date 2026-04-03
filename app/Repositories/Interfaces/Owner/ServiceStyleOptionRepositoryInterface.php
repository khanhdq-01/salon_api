<?php

namespace App\Repositories\Interfaces\Owner;

use App\Models\ServiceStyleOption;
use Illuminate\Support\Collection;

interface ServiceStyleOptionRepositoryInterface
{
    /**
     * @return Collection<int, ServiceStyleOption>
     */
    public function listBySalon(string $salonId): Collection;

    public function findWithService(string $id): ?ServiceStyleOption;

    public function create(array $data): ServiceStyleOption;

    public function updateByServiceAndId(string $serviceId, string $id, array $payload): void;
}
