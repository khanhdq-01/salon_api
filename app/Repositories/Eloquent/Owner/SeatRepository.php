<?php

namespace App\Repositories\Eloquent\Owner;

use App\Models\Seat;
use App\Repositories\Interfaces\Owner\SeatRepositoryInterface;
use Illuminate\Support\Collection;

class SeatRepository implements SeatRepositoryInterface
{
    public function __construct(
        protected Seat $model
    ) {}

    public function getActiveBySalonIds(array $salonIds): Collection
    {
        return $this->model->newQuery()
            ->active()
            ->whereIn('salon_id', $salonIds)
            ->get(['id', 'salon_id']);
    }

    public function getActiveBySalon(string $salonId): Collection
    {
        return $this->model->newQuery()
            ->where('salon_id', $salonId)
            ->active()
            ->get(['id', 'name']);
    }

    public function findActiveByIdAndSalon(string $seatId, string $salonId): ?Seat
    {
        return $this->model->newQuery()
            ->whereKey($seatId)
            ->where('salon_id', $salonId)
            ->active()
            ->first();
    }

    public function create(array $data): Seat
    {
        return $this->model->newQuery()->create($data);
    }
}
