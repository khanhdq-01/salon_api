<?php

namespace App\Http\Requests\Api\V1\Owner;

use App\Http\Requests\Concerns\ValidatesStorageImagePaths;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreServiceRequest extends FormRequest
{
    use ValidatesStorageImagePaths;
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'salon_id' => ['required', 'uuid', 'exists:salons,id'],
            'name' => ['required', 'string', 'max:200'],
            'price' => ['required', 'integer', 'min:0'],
            'duration_minutes' => ['required', 'integer', 'min:5', 'max:480'],
            'is_active' => ['nullable', 'boolean'],
            'style_options' => ['nullable', 'array'],
            'style_options.*.id' => ['nullable', 'uuid'],
            'style_options.*.name' => ['required_with:style_options', 'string', 'max:200'],
            'style_options.*.gender' => ['nullable', 'string', Rule::in(['male', 'female', 'unisex'])],
            'style_options.*.description' => ['nullable', 'string', 'max:1000'],
            'style_options.*.article' => ['nullable', 'string', 'max:10000'],
            'style_options.*.extra_price' => ['nullable', 'integer', 'min:0'],
            'style_options.*.extra_duration' => ['nullable', 'integer', 'min:0', 'max:480'],
            'style_options.*.image' => $this->storageImagePathRule(['style-options']),
            'style_options.*.sort_order' => ['nullable', 'integer', 'min:0'],
            'style_options.*.is_active' => ['nullable', 'boolean'],
            'style_options.*.is_featured' => ['nullable', 'boolean'],
        ];
    }
}
