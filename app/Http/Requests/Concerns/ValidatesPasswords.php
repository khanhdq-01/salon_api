<?php

namespace App\Http\Requests\Concerns;

use App\Support\PasswordRules;

trait ValidatesPasswords
{
    /**
     * @return list<string|\Illuminate\Validation\Rules\Password>
     */
    protected function requiredPasswordRule(bool $confirmed = false): array
    {
        return PasswordRules::required($confirmed);
    }

    /**
     * @return list<string|\Illuminate\Validation\Rules\Password>
     */
    protected function optionalPasswordRule(): array
    {
        return PasswordRules::optional();
    }
}
