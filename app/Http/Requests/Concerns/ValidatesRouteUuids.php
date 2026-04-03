<?php

namespace App\Http\Requests\Concerns;

trait ValidatesRouteUuids
{
    /** @return list<string> */
    abstract protected function routeUuidParameters(): array;

    protected function prepareRouteUuidValidation(): void
    {
        foreach ($this->routeUuidParameters() as $parameter) {
            $value = $this->route($parameter);

            if ($value !== null) {
                $this->merge([$parameter => $value]);
            }
        }
    }

    /** @return array<string, list<string>> */
    protected function routeUuidRules(): array
    {
        $rules = [];

        foreach ($this->routeUuidParameters() as $parameter) {
            $rules[$parameter] = ['required', 'uuid'];
        }

        return $rules;
    }
}
