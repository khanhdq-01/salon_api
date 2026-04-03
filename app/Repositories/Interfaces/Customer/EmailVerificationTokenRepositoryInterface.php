<?php

namespace App\Repositories\Interfaces\Customer;

use App\Models\EmailVerificationToken;
use App\Models\User;

interface EmailVerificationTokenRepositoryInterface
{
    public function createForUser(User $user, string $tokenHash, \DateTimeInterface $expiresAt): EmailVerificationToken;

    public function invalidateActiveTokensForUser(User $user): void;

    public function findLatestActiveForUser(User $user): ?EmailVerificationToken;

    public function findValidForUser(User $user, string $plainToken): ?EmailVerificationToken;

    public function markConsumed(EmailVerificationToken $token): void;
}
