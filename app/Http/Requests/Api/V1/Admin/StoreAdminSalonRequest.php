<?php

namespace App\Http\Requests\Api\V1\Admin;

use App\Http\Requests\Concerns\ValidatesStorageImagePaths;
use App\Models\Salon;
use Illuminate\Validation\Rule;

class StoreAdminSalonRequest extends AdminAuthorizedRequest
{
    use ValidatesStorageImagePaths;
    public function rules(): array
    {
        return [
            'owner_id' => ['required', 'uuid', 'exists:users,id'],
            'name' => ['required', 'string', 'max:200'],
            'address' => ['required', 'string', 'max:500'],
            'phone' => ['nullable', 'string', 'max:20'],
            'description' => ['nullable', 'string'],
            'lat' => ['nullable', 'numeric', 'between:-90,90'],
            'lng' => ['nullable', 'numeric', 'between:-180,180'],
            'image_url' => $this->storageImagePathRule(['salon-gallery', 'style-options'], true, ['img-salon']),
            'open_time' => ['nullable', 'date_format:H:i'],
            'close_time' => ['nullable', 'date_format:H:i', 'after:open_time'],
            'status' => ['nullable', Rule::in([Salon::STATUS_OPEN, Salon::STATUS_CLOSED])],
            'approval_status' => ['nullable', Rule::in([
                Salon::APPROVAL_PENDING,
                Salon::APPROVAL_APPROVED,
                Salon::APPROVAL_REJECTED,
            ])],
        ];
    }
}
