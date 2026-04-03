<?php

namespace App\Repositories\Interfaces\Customer;

use App\Models\ServiceStyleOption;
use Illuminate\Support\Collection;

interface ServiceStyleOptionRepositoryInterface
{
    public function listTrendingByBookingCount(int $limit, ?string $gender = null): Collection;

    public function getActiveByIdsForPublicSalons(array $ids): Collection;

    public function findActivePublicById(string $id): ?ServiceStyleOption;

    public function getFeaturedBySalon(string $salonId): Collection;

    public function findActiveBySalonAndId(string $salonId, string $styleId): ?ServiceStyleOption;
}
