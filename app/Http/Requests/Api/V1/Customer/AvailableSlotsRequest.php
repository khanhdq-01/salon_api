<?php

namespace App\Http\Requests\Api\V1\Customer;

use Illuminate\Foundation\Http\FormRequest;

class AvailableSlotsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'salonId' => ['sometimes', 'uuid'],
            'salon_id' => ['sometimes', 'uuid', 'exists:salons,id'],
            'date' => ['required', 'date', 'after_or_equal:today'],
            'service_ids' => ['required', 'array', 'min:1'],
            'service_ids.*' => ['uuid', 'exists:services,id'],
            'style_options' => ['nullable', 'array'],
            'style_options.*' => ['nullable', 'uuid', 'exists:service_style_options,id'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->route('salonId') && ! $this->has('salon_id')) {
            $this->merge(['salon_id' => $this->route('salonId')]);
        }

        if ($this->route('salonId')) {
            $this->merge(['salonId' => $this->route('salonId')]);
        }

        $serviceIds = $this->input('service_ids');

        if (is_string($serviceIds)) {
            $decoded = json_decode($serviceIds, true);
            $serviceIds = is_array($decoded)
                ? $decoded
                : array_values(array_filter(array_map('trim', explode(',', $serviceIds))));
        }

        if ($serviceIds !== null && ! is_array($serviceIds)) {
            $serviceIds = [$serviceIds];
        }

        if (is_array($serviceIds)) {
            $this->merge(['service_ids' => array_values($serviceIds)]);
        }

        $styleOptions = $this->input('style_options');

        if (is_string($styleOptions)) {
            $decoded = json_decode($styleOptions, true);
            if (is_array($decoded)) {
                $this->merge(['style_options' => $decoded]);
            }
        }
    }
}
