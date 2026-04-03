<?php

namespace App\Http\Requests\Concerns;

trait CastsQueryBooleans
{
    /**
     * Cast query-string booleans ("true", "false", "1", "0") before validation.
     *
     * @param  list<string>  $fields
     */
    protected function castQueryBooleans(array $fields): void
    {
        $merged = [];

        foreach ($fields as $field) {
            if (! $this->exists($field)) {
                continue;
            }

            $value = $this->input($field);

            if ($value === null || $value === '' || is_bool($value)) {
                continue;
            }

            $merged[$field] = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        }

        if ($merged !== []) {
            $this->merge($merged);
        }
    }
}
