<?php

namespace App\Policies;

use App\Models\ServiceStyleOption;
use App\Models\User;

class ServiceStyleOptionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isOwner();
    }

    public function view(User $user, ServiceStyleOption $styleOption): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        $styleOption->loadMissing('service.salon');

        return $user->isOwner()
            && $styleOption->service?->salon?->owner_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isOwner();
    }

    public function update(User $user, ServiceStyleOption $styleOption): bool
    {
        return $this->view($user, $styleOption);
    }

    public function delete(User $user, ServiceStyleOption $styleOption): bool
    {
        return $this->view($user, $styleOption);
    }
}
