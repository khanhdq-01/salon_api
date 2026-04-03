<?php

namespace Tests\Unit\Support;

use App\Support\SensitiveDataRedactor;
use PHPUnit\Framework\TestCase;

class SensitiveDataRedactorTest extends TestCase
{
    public function test_redacts_known_sensitive_keys(): void
    {
        $input = [
            'email' => 'user@example.com',
            'password' => 'Secret123!',
            'token' => 'jwt-token-value',
            'reset_url' => 'https://app.test/reset?token=abc',
            'meta' => [
                'access_token' => 'nested-token',
            ],
        ];

        $result = SensitiveDataRedactor::redact($input);

        $this->assertSame('user@example.com', $result['email']);
        $this->assertSame(SensitiveDataRedactor::REDACTED, $result['password']);
        $this->assertSame(SensitiveDataRedactor::REDACTED, $result['token']);
        $this->assertSame(SensitiveDataRedactor::REDACTED, $result['reset_url']);
        $this->assertSame(SensitiveDataRedactor::REDACTED, $result['meta']['access_token']);
    }

    public function test_redacts_suffix_based_keys(): void
    {
        $this->assertTrue(SensitiveDataRedactor::isSensitiveKey('client_secret'));
        $this->assertTrue(SensitiveDataRedactor::isSensitiveKey('refresh_token'));
        $this->assertFalse(SensitiveDataRedactor::isSensitiveKey('email'));
    }
}
