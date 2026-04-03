<?php

namespace App\Repositories\Eloquent\Customer;

use App\Models\EmailVerificationToken;
use App\Models\User;
use App\Repositories\Interfaces\Customer\EmailVerificationTokenRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class EmailVerificationTokenRepository implements EmailVerificationTokenRepositoryInterface
{
    public function createForUser(User $user, string $tokenHash, \DateTimeInterface $expiresAt): EmailVerificationToken
    {
        return EmailVerificationToken::query()->create([
            'id' => (string) Str::uuid(),
            'user_id' => $user->id,
            'token_hash' => $tokenHash,
            'expires_at' => $expiresAt,
            'created_at' => now(),
        ]);
    }

    public function invalidateActiveTokensForUser(User $user): void
    {
        EmailVerificationToken::query()
            ->where('user_id', $user->id)
            ->whereNull('consumed_at')
            ->where('expires_at', '>', now())
            ->update(['consumed_at' => now()]);
    }

    public function findLatestActiveForUser(User $user): ?EmailVerificationToken
    {
        return EmailVerificationToken::query()
            ->where('user_id', $user->id)
            ->whereNull('consumed_at')
            ->where('expires_at', '>', now())
            ->orderByDesc('created_at')
            ->first();
    }

    public function findValidForUser(User $user, string $plainToken): ?EmailVerificationToken
    {
        $tokens = EmailVerificationToken::query()
            ->where('user_id', $user->id)
            ->whereNull('consumed_at')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        foreach ($tokens as $token) {
            if ($token->isExpired()) {
                continue;
            }

            if (Hash::check($plainToken, $token->token_hash)) {
                return $token;
            }
        }

        return null;
    }

    public function markConsumed(EmailVerificationToken $token): void
    {
        $token->consumed_at = now();
        $token->save();
    }
}
