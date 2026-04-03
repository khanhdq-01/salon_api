<?php

namespace App\Contracts\Services\Owner;

use App\Models\Salon;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface SalonServiceInterface
{
    public function createSalon(array $data, User $actor): Salon;

    public function updateSalon(string $id, array $data, User $actor): Salon;

    public function updateSalonStatus(string $id, array $data, User $actor): Salon;

    public function deleteSalon(string $id, User $actor): bool;

    public function restoreSalon(string $id, User $actor): Salon;

    public function getSalonById(string $id, ?User $actor = null): Salon;

    public function listSalons(array $filters, ?User $actor = null): LengthAwarePaginator;

    public function findSalonOrFail(string $id, ?User $actor = null): Salon;

    public function getOwnerSalon(User $owner): Salon;
}
