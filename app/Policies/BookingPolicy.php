<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;

class BookingPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Booking $booking): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isCustomer() && $booking->customer_id === $user->id) {
            return true;
        }

        return $user->isOwner() && $booking->salon?->owner_id === $user->id;
    }

    public function update(User $user, Booking $booking): bool
    {
        return $this->manage($user, $booking);
    }

    public function delete(User $user, Booking $booking): bool
    {
        return $user->isAdmin() || $this->manage($user, $booking);
    }

    public function create(User $user): bool
    {
        return $user->isCustomer() || $user->isAdmin();
    }

    public function manage(User $user, Booking $booking): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->isOwner() && $booking->salon?->owner_id === $user->id;
    }

    public function cancel(User $user, Booking $booking): bool
    {
        if ($this->manage($user, $booking)) {
            return true;
        }

        return $user->isCustomer() && $booking->customer_id === $user->id;
    }

    public function reschedule(User $user, Booking $booking): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($this->manage($user, $booking)) {
            return true;
        }

        return $user->isCustomer() && $booking->customer_id === $user->id;
    }
}
