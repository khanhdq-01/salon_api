<?php

namespace App\Contracts\Services\Customer;

use App\Models\User;

interface AuthServiceInterface
{
    public function register(array $data): array;

    public function login(array $credentials): string;

    public function changePassword(User $user, string $password): void;

    public function resetPassword(string $email, string $token, string $password): void;

    public function forgotPassword(string $email): void;

    public function logout(): void;

    public function logoutAllDevices(User $user): void;
}