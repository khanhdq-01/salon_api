<?php

namespace App\Policies;

use App\Models\Review;
use App\Models\User;

class ReviewPolicy
{
    public function view(?User $user, Review $review): bool
    {
        return true;
    }

    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->isCustomer();
    }

    public function update(User $user, Review $review): bool
    {
        return $user->isAdmin() || $review->customer_id === $user->id;
    }

    public function delete(User $user, Review $review): bool
    {
        return $this->update($user, $review);
    }

    public function report(User $user, Review $review): bool
    {
        return $user !== null;
    }
}
