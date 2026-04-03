<?php

namespace App\Http\Requests\Api\V1\Customer;

use App\Models\Salon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ListSalonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'q' => ['nullable', 'string', 'max:255'],
            'rating_min' => ['nullable', 'numeric', 'min:0', 'max:5'],
            'status' => ['nullable', Rule::in([Salon::STATUS_OPEN, Salon::STATUS_CLOSED])],
            'approval_status' => ['nullable', Rule::in([
                Salon::APPROVAL_PENDING,
                Salon::APPROVAL_APPROVED,
                Salon::APPROVAL_REJECTED,
            ])],
            'owner_id' => ['nullable', 'uuid', 'exists:users,id'],
            'lat' => ['nullable', 'numeric', 'between:-90,90'],
            'lng' => ['nullable', 'numeric', 'between:-180,180'],
            'distance' => ['nullable', 'numeric', 'min:0'],
            'distance_km' => ['nullable', 'numeric', 'min:0'],
            'available_today' => ['nullable', 'boolean'],
            'sort' => ['nullable', 'string', 'in:newest,rating'],
            'page' => ['nullable', 'integer', 'min:1'],
            'limit' => ['nullable', 'integer', 'min:1', 'max:100'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }
}
