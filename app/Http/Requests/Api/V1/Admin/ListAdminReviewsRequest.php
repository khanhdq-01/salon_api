<?php

namespace App\Http\Requests\Api\V1\Admin;

use App\Http\Requests\Concerns\CastsQueryBooleans;

class ListAdminReviewsRequest extends AdminAuthorizedRequest
{
    use CastsQueryBooleans;

    protected function prepareForValidation(): void
    {
        $this->castQueryBooleans(['hidden']);
    }

    public function rules(): array
    {
        return [
            'q' => ['nullable', 'string', 'max:200'],
            'salon_id' => ['nullable', 'uuid', 'exists:salons,id'],
            'hidden' => ['nullable', 'boolean'],
            'page' => ['nullable', 'integer', 'min:1'],
            'limit' => ['nullable', 'integer', 'min:1', 'max:100'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }
}
