<?php

namespace App\Http\Requests\Api\V1\Admin;

use App\Models\Subscription;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ListAdminSubscriptionsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'q' => ['nullable', 'string', 'max:200'],
            'status' => ['nullable', Rule::in($this->allowedStatuses())],
            'owner_id' => ['nullable', 'uuid', 'exists:users,id'],
            'package_id' => ['nullable', 'uuid', 'exists:packages,id'],
            'page' => ['nullable', 'integer', 'min:1'],
            'limit' => ['nullable', 'integer', 'min:1', 'max:100'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }

    private function allowedStatuses(): array
    {
        return [
            ...Subscription::STATUSES,
            'Pending Approval',
            'Approved',
            'Rejected',
            'Active',
            'Expired',
            'Canceled',
            'Cancelled',
        ];
    }
}
