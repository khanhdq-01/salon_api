<?php

namespace App\Http\Requests\Api\V1\Admin;

use App\Models\ReviewReport;
use Illuminate\Validation\Rule;

class ListAdminReviewReportsRequest extends AdminAuthorizedRequest
{
    public function rules(): array
    {
        return [
            'status' => ['nullable', Rule::in([
                ReviewReport::STATUS_PENDING,
                ReviewReport::STATUS_RESOLVED,
                ReviewReport::STATUS_DISMISSED,
            ])],
            'page' => ['nullable', 'integer', 'min:1'],
            'limit' => ['nullable', 'integer', 'min:1', 'max:100'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }
}
