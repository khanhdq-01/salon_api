<?php

namespace App\Http\Requests\Api\V1\Owner;

use App\Http\Requests\Concerns\ValidatesStorageImagePaths;
use App\Models\Salon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSalonRequest extends FormRequest
{
    use ValidatesStorageImagePaths;
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:200'],
            'address' => ['required', 'string', 'max:500'],
            'phone' => ['nullable', 'string', 'max:20'],
            'description' => ['nullable', 'string'],
            'owner_id' => [
                $this->user()?->isAdmin() ? 'required' : 'nullable',
                'uuid',
                'exists:users,id',
            ],
            'lat' => ['nullable', 'numeric', 'between:-90,90'],
            'lng' => ['nullable', 'numeric', 'between:-180,180'],
            'image_url' => $this->storageImagePathRule(['salon-gallery', 'style-options'], true, ['img-salon']),
            'open_time' => ['nullable', 'date_format:H:i'],
            'close_time' => ['nullable', 'date_format:H:i', 'after:open_time'],
            'status' => ['nullable', Rule::in([Salon::STATUS_OPEN, Salon::STATUS_CLOSED])],
            'package_id' => ['required', 'uuid', 'exists:packages,id'],
        ];

        return $rules;
    }
}
