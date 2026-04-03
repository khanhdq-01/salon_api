<?php

namespace App\Repositories\Interfaces\Owner;

use App\Models\Seat;

interface SeatRepositoryInterface
{
    public function getActiveBySalonIds(array $salonIds): \Illuminate\Support\Collection;

    public function getActiveBySalon(string $salonId): \Illuminate\Support\Collection;

    public function findActiveByIdAndSalon(string $seatId, string $salonId): ?Seat;

    public function create(array $data): Seat;
}
