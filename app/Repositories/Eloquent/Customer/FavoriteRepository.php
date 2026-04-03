<?php

namespace App\Repositories\Eloquent\Customer;

use App\Models\FavoriteProduct;
use App\Models\Salon;
use App\Models\ServiceStyleOption;
use App\Models\User;
use App\Repositories\Interfaces\Customer\FavoriteRepositoryInterface;
use Illuminate\Support\Collection;

class FavoriteRepository implements FavoriteRepositoryInterface
{
    public function __construct(
        protected Salon $salonModel,
        protected ServiceStyleOption $styleOptionModel,
    ) {}

    public function getFavoriteSalons(User $user): Collection
    {
        return $user->favoriteSalons()
            ->publiclyVisible()
            ->orderByPivot('created_at', 'desc')
            ->get();
    }

    public function findPublicSalonById(string $salonId): ?Salon
    {
        return $this->salonModel->newQuery()->publiclyVisible()->find($salonId);
    }

    public function getFavoriteHairstyleRefs(User $user): Collection
    {
        return $user->favoriteProducts()
            ->ofType(FavoriteProduct::TYPE_HAIRSTYLE)
            ->orderByDesc('created_at')
            ->pluck('product_ref');
    }

    public function getActiveHairstylesByRefs(Collection $refs): Collection
    {
        return $this->styleOptionModel->newQuery()
            ->whereIn('id', $refs)
            ->where('is_active', true)
            ->whereHas('service', fn ($query) => $query
                ->where('is_active', true)
                ->whereHas('salon', fn ($salonQuery) => $salonQuery->publiclyVisible()))
            ->with(['service:id,name,salon_id'])
            ->get();
    }

    public function findActivePublicHairstyleById(string $styleId): ?ServiceStyleOption
    {
        return $this->styleOptionModel->newQuery()
            ->where('id', $styleId)
            ->where('is_active', true)
            ->whereHas('service', fn ($query) => $query
                ->where('is_active', true)
                ->whereHas('salon', fn ($salonQuery) => $salonQuery->publiclyVisible()))
            ->with(['service:id,name,salon_id'])
            ->first();
    }
}
