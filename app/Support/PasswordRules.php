<?php

namespace App\Support;

use Illuminate\Validation\Rules\Password;

final class PasswordRules
{
    public static function defaults(): Password
    {
        $rule = Password::min((int) config('password.min_length', 8))
            ->letters()
            ->mixedCase()
            ->numbers()
            ->symbols()
            ->max(100);

        if (config('password.uncompromised', false)) {
            $rule->uncompromised((int) config('password.uncompromised_threshold', 0));
        }

        return $rule;
    }

    /**
     * @return list<string|Password>
     */
    public static function required(bool $confirmed = false): array
    {
        $rules = ['required', 'string', self::defaults()];

        if ($confirmed) {
            $rules[] = 'confirmed';
        }

        return $rules;
    }

    /**
     * @return list<string|Password>
     */
    public static function optional(): array
    {
        return ['nullable', 'string', self::defaults()];
    }
}
