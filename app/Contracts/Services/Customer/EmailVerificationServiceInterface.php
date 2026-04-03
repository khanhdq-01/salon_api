<?php

namespace App\Contracts\Services\Customer;

use App\Models\User;

interface EmailVerificationServiceInterface
{
    public function registerAndSendVerification(User $user): void;

    public function verify(string $email, string $plainToken): User;

    public function resend(string $email): void;
}
