<?php

namespace App\Http\Requests\Api\V1\Owner;

use Illuminate\Foundation\Http\FormRequest;

class AssignStaffServicesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'service_ids' => ['required', 'array'],
            'service_ids.*' => ['uuid', 'exists:services,id'],
        ];
    }
}
