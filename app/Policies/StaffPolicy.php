<?php

namespace App\Policies;

use App\Models\Staff;
use App\Models\User;

class StaffPolicy
{
    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(?User $user, Staff $staff): bool
    {
        if ($staff->is_active && $staff->salon?->isPubliclyVisible()) {
            return true;
        }

        if (! $user) {
            return false;
        }

        return $user->isAdmin()
            || ($user->isOwner() && $staff->salon?->owner_id === $user->id);
    }

    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isOwner();
    }

    public function update(User $user, Staff $staff): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->isOwner() && $staff->salon?->owner_id === $user->id;
    }

    public function delete(User $user, Staff $staff): bool
    {
        return $this->update($user, $staff);
    }
}
