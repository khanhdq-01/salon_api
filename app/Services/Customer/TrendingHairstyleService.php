<?php

namespace App\Services\Customer;

use App\Repositories\Interfaces\Customer\ServiceStyleOptionRepositoryInterface;
use Illuminate\Support\Collection;

class TrendingHairstyleService
{
    public function __construct(
        protected ServiceStyleOptionRepositoryInterface $styleOptionRepository,
    ) {}

    public function listByBookingCount(int $limit = 24, ?string $gender = null): Collection
    {
        $limit = min(max($limit, 1), 48);

        return $this->styleOptionRepository->listTrendingByBookingCount($limit, $gender);
    }
}
