<?php

namespace App\Http\Requests\Api\V1\Owner;

use App\Models\SalonSetting;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOwnerSalonSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'auto_confirm_booking' => ['sometimes', 'boolean'],
            'customer_cancel_before_minutes' => [
                'sometimes',
                'integer',
                Rule::in(SalonSetting::ALLOWED_CANCEL_BEFORE_MINUTES),
            ],
            'booking_interval_minutes' => [
                'sometimes',
                'integer',
                Rule::in(SalonSetting::ALLOWED_BOOKING_INTERVAL_MINUTES),
            ],
            'auto_approve_work_schedule' => ['sometimes', 'boolean'],
        ];
    }
}
