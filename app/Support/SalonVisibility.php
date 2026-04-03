<?php

namespace App\Support;

use App\Exceptions\BusinessException;
use App\Models\Salon;
use App\Models\User;

class SalonVisibility
{
    public static function assertCustomerAccessible(Salon $salon, ?User $user = null): void
    {
        if ($salon->isPubliclyVisible()) {
            return;
        }

        if ($user?->isAdmin()) {
            return;
        }

        if ($user?->isOwner() && $salon->owner_id === $user->id) {
            return;
        }

        throw new BusinessException('Salon không khả dụng.', 'SALON_NOT_AVAILABLE', 404);
    }
}
