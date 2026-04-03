<?php

namespace App\Http\Requests\Api\V1\Admin;

use App\Models\Subscription;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAdminSubscriptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'owner_id' => ['sometimes', 'uuid', 'exists:users,id'],
            'package_id' => ['sometimes', 'uuid', 'exists:packages,id'],
            'status' => ['sometimes', Rule::in($this->allowedStatuses())],
            'start_date' => ['sometimes', 'date'],
            'end_date' => ['sometimes', 'date'],
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
