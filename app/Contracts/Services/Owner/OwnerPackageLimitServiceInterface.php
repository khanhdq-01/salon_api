<?php

namespace App\Contracts\Services\Owner;

use App\Models\User;

interface OwnerPackageLimitServiceInterface
{
    public function assertCanAddStaff(User $owner, string $salonId): void;

    public function assertCanAddService(User $owner, string $salonId): void;

    public function assertCanAddBookingForSalon(string $salonId): void;
}
