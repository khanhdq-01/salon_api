<?php

namespace App\Http\Requests\Api\V1\Admin;

use App\Models\Subscription;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAdminSubscriptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'owner_id' => ['required', 'uuid', 'exists:users,id'],
            'package_id' => ['required', 'uuid', 'exists:packages,id'],
            'status' => ['nullable', Rule::in($this->allowedStatuses())],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
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
