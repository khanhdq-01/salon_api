<?php

namespace App\Repositories\Interfaces\Customer;

use App\Models\User;

interface UserRepositoryInterface
{
    public function create(array $data): User;

    public function findByEmail(string $email): ?User;

    public function findById(string $id): ?User;

    public function updatePassword(User $user, string $password): User;

    public function markEmailVerified(User $user): User;
}