<?php

namespace App\Repositories\Eloquent\Customer;

use App\Models\Booking;
use App\Models\ServiceStyleOption;
use App\Repositories\Interfaces\Customer\ServiceStyleOptionRepositoryInterface;
use Illuminate\Support\Collection;

class ServiceStyleOptionRepository implements ServiceStyleOptionRepositoryInterface
{
    public function __construct(
        protected ServiceStyleOption $model
    ) {}

    public function listTrendingByBookingCount(int $limit, ?string $gender = null): Collection
    {
        $query = $this->model->newQuery()
            ->active()
            ->whereNotNull('image')
            ->where('image', '!=', '')
            ->whereHas('service', fn ($serviceQuery) => $serviceQuery
                ->active()
                ->whereHas('salon', fn ($salonQuery) => $salonQuery->publiclyVisible()))
            ->with([
                'service:id,name,salon_id,price',
                'service.salon:id,name,image_url',
                'service.salon.images' => fn ($imageQuery) => $imageQuery->orderByDesc('created_at')->limit(1),
            ])
            ->withCount([
                'bookingServices as bookings_count' => fn ($countQuery) => $countQuery->whereHas(
                    'booking',
                    fn ($bookingQuery) => $bookingQuery
                        ->whereNull('deleted_at')
                        ->where('status', '!=', Booking::STATUS_CANCELLED),
                ),
            ])
            ->having('bookings_count', '>', 0)
            ->orderByDesc('bookings_count')
            ->orderBy('name');

        if ($gender !== null) {
            $query->where('gender', $gender);
        }

        return $query
            ->limit($limit)
            ->get();
    }

    public function getActiveByIdsForPublicSalons(array $ids): Collection
    {
        return $this->model->newQuery()
            ->whereIn('id', $ids)
            ->where('is_active', true)
            ->whereHas('service', fn ($query) => $query
                ->where('is_active', true)
                ->whereHas('salon', fn ($salonQuery) => $salonQuery->publiclyVisible()))
            ->with(['service:id,name,salon_id'])
            ->get();
    }

    public function findActivePublicById(string $id): ?ServiceStyleOption
    {
        return $this->model->newQuery()
            ->where('id', $id)
            ->where('is_active', true)
            ->whereHas('service', fn ($query) => $query
                ->where('is_active', true)
                ->whereHas('salon', fn ($salonQuery) => $salonQuery->publiclyVisible()))
            ->with(['service:id,name,salon_id'])
            ->first();
    }

    public function getFeaturedBySalon(string $salonId): Collection
    {
        return $this->model->newQuery()
            ->where('is_active', true)
            ->where('is_featured', true)
            ->whereNotNull('image')
            ->where('image', '!=', '')
            ->whereHas('service', fn ($query) => $query
                ->where('salon_id', $salonId)
                ->where('is_active', true))
            ->with(['service:id,name,salon_id'])
            ->orderBy('sort_order')
            ->orderByDesc('created_at')
            ->get();
    }

    public function findActiveBySalonAndId(string $salonId, string $styleId): ?ServiceStyleOption
    {
        return $this->model->newQuery()
            ->where('id', $styleId)
            ->where('is_active', true)
            ->whereHas('service', fn ($query) => $query->where('salon_id', $salonId))
            ->with(['service:id,name,salon_id'])
            ->first();
    }
}
