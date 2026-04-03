<?php

namespace App\Policies;

use App\Models\Salon;
use App\Models\User;

class SalonPolicy
{
    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(?User $user, Salon $salon): bool
    {
        if ($salon->isVisibleToPublic()) {
            return true;
        }

        if (! $user) {
            return false;
        }

        if ($user->isAdmin()) {
            return true;
        }

        return $user->isOwner() && $salon->owner_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->isOwner();
    }

    public function update(User $user, Salon $salon): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->isOwner() && $salon->owner_id === $user->id;
    }

    public function delete(User $user, Salon $salon): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->isOwner() && $salon->owner_id === $user->id;
    }

    public function updateStatus(User $user, Salon $salon): bool
    {
        return $this->update($user, $salon);
    }
}
