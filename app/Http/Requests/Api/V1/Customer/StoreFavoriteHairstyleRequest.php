<?php

namespace App\Http\Requests\Api\V1\Customer;

use Illuminate\Foundation\Http\FormRequest;

class StoreFavoriteHairstyleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'style_option_id' => ['required', 'uuid', 'exists:service_style_options,id'],
        ];
    }
}
