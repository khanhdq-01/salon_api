<?php

namespace Tests\Unit\Support;

use App\Support\PasswordRules;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class PasswordRulesTest extends TestCase
{
    public function test_weak_password_fails_policy(): void
    {
        Config::set('password.uncompromised', false);

        $validator = Validator::make(
            ['password' => '123456'],
            ['password' => PasswordRules::required()]
        );

        $this->assertTrue($validator->fails());
    }

    public function test_strong_password_passes_policy(): void
    {
        Config::set('password.uncompromised', false);

        $validator = Validator::make(
            ['password' => 'Salon@2026'],
            ['password' => PasswordRules::required()]
        );

        $this->assertFalse($validator->fails());
    }

    public function test_optional_password_allows_null(): void
    {
        Config::set('password.uncompromised', false);

        $validator = Validator::make(
            ['password' => null],
            ['password' => PasswordRules::optional()]
        );

        $this->assertFalse($validator->fails());
    }
}
