<?php

namespace App\Policies;

use App\Models\Service;
use App\Models\User;

class ServicePolicy
{
    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(?User $user, Service $service): bool
    {
        if ($service->is_active && $service->salon?->isPubliclyVisible()) {
            return true;
        }

        if (! $user) {
            return false;
        }

        return $user->isAdmin()
            || ($user->isOwner() && $service->salon?->owner_id === $user->id);
    }

    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isOwner();
    }

    public function update(User $user, Service $service): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->isOwner() && $service->salon?->owner_id === $user->id;
    }

    public function delete(User $user, Service $service): bool
    {
        return $this->update($user, $service);
    }
}
