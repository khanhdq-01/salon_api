<?php

namespace App\Http\Requests\Api\V1\Owner;

use App\Http\Requests\Concerns\ValidatesStorageImagePaths;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStyleOptionRequest extends FormRequest
{
    use ValidatesStorageImagePaths;
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'service_id' => ['sometimes', 'required', 'uuid', 'exists:services,id'],
            'name' => ['sometimes', 'required', 'string', 'max:200'],
            'gender' => ['nullable', 'string', Rule::in(['male', 'female', 'unisex'])],
            'description' => ['nullable', 'string', 'max:1000'],
            'article' => ['nullable', 'string', 'max:50000'],
            'extra_price' => ['nullable', 'integer', 'min:0'],
            'extra_duration' => ['nullable', 'integer', 'min:0', 'max:480'],
            'image' => $this->storageImagePathRule(['style-options']),
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
        ];
    }
}
