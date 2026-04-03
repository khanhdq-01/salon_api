<?php

namespace App\Http\Requests\Api\V1\Customer;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'salon_id' => ['required', 'uuid', 'exists:salons,id'],
            'service_ids' => ['required', 'array', 'min:1'],
            'service_ids.*' => ['uuid', 'exists:services,id'],
            'style_options' => ['nullable', 'array'],
            'style_options.*' => ['nullable', 'uuid', 'exists:service_style_options,id'],
            'date' => ['required', 'date', 'after_or_equal:today'],
            'time' => ['required', 'date_format:H:i'],
            'seat_id' => ['nullable', 'uuid', 'exists:seats,id'],
            'staff_id' => ['required', 'uuid', 'exists:staff,id'],
            'customer_notes' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
