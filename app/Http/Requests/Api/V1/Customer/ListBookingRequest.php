<?php

namespace App\Http\Requests\Api\V1\Customer;

use App\Models\Booking;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ListBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'salon_id' => ['nullable', 'uuid', 'exists:salons,id'],
            'status' => ['nullable', Rule::in([
                Booking::STATUS_PENDING,
                Booking::STATUS_CONFIRMED,
                Booking::STATUS_COMPLETED,
                Booking::STATUS_CANCELLED,
                Booking::STATUS_NO_SHOW,
            ])],
            'date' => ['nullable', 'date'],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
            'start_date' => ['nullable', 'date'],
            'end_date' => [
                'nullable',
                'date',
                Rule::when(
                    filled($this->input('start_date')),
                    ['after_or_equal:start_date']
                ),
            ],
            'staff_id' => ['nullable', 'uuid', 'exists:staff,id'],
            'page' => ['nullable', 'integer', 'min:1'],
            'limit' => ['nullable', 'integer', Rule::in([10, 20, 50])],
            'per_page' => ['nullable', 'integer', Rule::in([10, 20, 50])],
            'sort' => ['nullable', 'string', Rule::in(['created_at', 'appointment'])],
        ];
    }
}
