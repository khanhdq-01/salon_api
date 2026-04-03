<?php

namespace App\Repositories\Interfaces\Customer;

use App\Models\Salon;
use App\Models\ServiceStyleOption;
use App\Models\User;
use Illuminate\Support\Collection;

interface FavoriteRepositoryInterface
{
    public function getFavoriteSalons(User $user): Collection;

    public function findPublicSalonById(string $salonId): ?Salon;

    public function getFavoriteHairstyleRefs(User $user): Collection;

    public function getActiveHairstylesByRefs(Collection $refs): Collection;

    public function findActivePublicHairstyleById(string $styleId): ?ServiceStyleOption;
}
