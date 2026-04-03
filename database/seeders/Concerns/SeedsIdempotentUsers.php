<?php

namespace Database\Seeders\Concerns;

use App\Models\Role;
use App\Models\User;
use Database\Seeders\Support\DemoSeederConstants;
use Illuminate\Support\Facades\Hash;

trait SeedsIdempotentUsers
{
    /**
     * @param  array{
     *     role_id: int,
     *     name: string,
     *     email: string,
     *     phone?: string|null,
     *     address?: string|null,
     *     avatar_url?: string|null,
     *     last_login_days_ago?: int,
     *     owner_id?: string|null,
     * }  $payload
     */
    protected function seedUser(array $payload): User
    {
        $email = strtolower(trim($payload['email']));

        return User::query()->updateOrCreate(
            ['email' => $email],
            [
                'role_id' => $payload['role_id'],
                'name' => $payload['name'],
                'password' => Hash::make(DemoSeederConstants::PASSWORD),
                'phone' => $payload['phone'] ?? null,
                'address' => $payload['address'] ?? null,
                'avatar_url' => $payload['avatar_url'] ?? null,
                'status' => User::STATUS_ACTIVE,
                'token_version' => 0,
                'owner_id' => $payload['owner_id'] ?? null,
                'last_login' => now()->subDays($payload['last_login_days_ago'] ?? 0),
            ]
        );
    }
}
