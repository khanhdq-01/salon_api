<?php

namespace App\Policies;

use App\Models\StaffSchedule;
use App\Models\User;

class StaffSchedulePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isOwner() || $user->isAdmin();
    }

    public function view(User $user, StaffSchedule $schedule): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isOwner()) {
            return $schedule->staff?->salon?->owner_id === $user->id;
        }

        if ($user->isStaff()) {
            return $schedule->staff?->user_id === $user->id;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->isOwner() || $user->isAdmin();
    }

    public function update(User $user, StaffSchedule $schedule): bool
    {
        return $this->view($user, $schedule) && ($user->isOwner() || $user->isAdmin());
    }

    public function delete(User $user, StaffSchedule $schedule): bool
    {
        return $this->update($user, $schedule);
    }

    public function approve(User $user, StaffSchedule $schedule): bool
    {
        return $user->isOwner() && $schedule->staff?->salon?->owner_id === $user->id;
    }

    public function reject(User $user, StaffSchedule $schedule): bool
    {
        return $this->approve($user, $schedule);
    }
}
