<?php

namespace App\Http\Requests\Api\V1\Admin;

use App\Models\Package;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAdminPackageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:100'],
            'type' => ['sometimes', 'string', Rule::in(['basic', 'premium', 'Basic', 'Premium'])],
            'price' => ['sometimes', 'integer', 'min:0'],
            'billing_period' => ['sometimes', 'string', Rule::in([
                Package::BILLING_1_MONTH,
                Package::BILLING_3_MONTHS,
                Package::BILLING_1_YEAR,
            ])],
            'description' => ['nullable', 'string'],
            'max_staff' => ['nullable', 'integer', 'min:1'],
            'max_services' => ['nullable', 'integer', 'min:1'],
            'max_bookings_per_month' => ['nullable', 'integer', 'min:1'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
