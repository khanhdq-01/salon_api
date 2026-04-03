<?php

namespace App\Services\Owner;

use App\Repositories\Interfaces\Owner\ServiceRepositoryInterface;
use Illuminate\Support\Collection;

class PopularServiceService
{
    public function __construct(
        protected ServiceRepositoryInterface $serviceRepository,
    ) {}

    public function listPopular(int $limit = 8): Collection
    {
        $limit = min(max($limit, 1), 20);

        return $this->serviceRepository->listPopular($limit);
    }
}
