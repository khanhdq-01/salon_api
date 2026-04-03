<?php

namespace App\Support;

final class SensitiveDataRedactor
{
    /** @var list<string> */
    private const EXACT_KEYS = [
        'password',
        'password_confirmation',
        'current_password',
        'old_password',
        'new_password',
        'token',
        'reset_token',
        'access_token',
        'refresh_token',
        'api_token',
        'authorization',
        'secret',
        'jwt_secret',
        'api_key',
        'client_secret',
        'private_key',
        'credit_card',
        'cvv',
        'reset_url',
    ];

    /** @var list<string> */
    private const SENSITIVE_SUFFIXES = [
        'password',
        'token',
        'secret',
    ];

    public const REDACTED = '[REDACTED]';

    /**
     * @param  mixed  $data
     * @return mixed
     */
    public static function redact(mixed $data): mixed
    {
        if (! is_array($data)) {
            return $data;
        }

        $redacted = [];

        foreach ($data as $key => $value) {
            if (self::isSensitiveKey((string) $key)) {
                $redacted[$key] = self::REDACTED;

                continue;
            }

            $redacted[$key] = is_array($value) ? self::redact($value) : $value;
        }

        return $redacted;
    }

    public static function isSensitiveKey(string $key): bool
    {
        $normalized = strtolower(str_replace('-', '_', $key));

        if (in_array($normalized, self::EXACT_KEYS, true)) {
            return true;
        }

        foreach (self::SENSITIVE_SUFFIXES as $suffix) {
            if (str_ends_with($normalized, '_'.$suffix) || str_ends_with($normalized, $suffix)) {
                return true;
            }
        }

        return false;
    }
}
