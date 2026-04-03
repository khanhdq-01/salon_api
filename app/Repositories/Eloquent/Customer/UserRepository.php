<?php

namespace App\Repositories\Eloquent\Customer;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Repositories\Interfaces\Customer\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public function create(array $data): User
    {
        return User::create($data);
    }

    public function findByEmail(string $email): ?User
    {
        return User::query()->with('role')->where('email', $email)->first();
    }

    public function findById(string $id): ?User
    {
        return User::query()->find($id);
    }

    public function updatePassword(User $user, string $password): User
    {
        $user->password = Hash::make($password);
        $user->token_version++;
        $user->save();

        return $user;
    }

    public function markEmailVerified(User $user): User
    {
        $user->email_verified_at = now();
        $user->status = User::STATUS_ACTIVE;
        $user->save();

        return $user;
    }
}