<?php

namespace App\Http\Requests\Api\V1\Admin;

use App\Http\Requests\Concerns\ValidatesRouteUuids;
use App\Http\Requests\Concerns\ValidatesStorageImagePaths;
use App\Models\Salon;
use Illuminate\Validation\Rule;

class UpdateAdminSalonRequest extends AdminAuthorizedRequest
{
    use ValidatesRouteUuids;
    use ValidatesStorageImagePaths;

    protected function prepareForValidation(): void
    {
        $this->prepareRouteUuidValidation();
    }

    /** @return list<string> */
    protected function routeUuidParameters(): array
    {
        return ['id'];
    }

    public function rules(): array
    {
        return array_merge($this->routeUuidRules(), [
            'name' => ['sometimes', 'string', 'max:200'],
            'address' => ['sometimes', 'string', 'max:500'],
            'phone' => ['nullable', 'string', 'max:20'],
            'description' => ['nullable', 'string'],
            'lat' => ['nullable', 'numeric', 'between:-90,90'],
            'lng' => ['nullable', 'numeric', 'between:-180,180'],
            'image_url' => $this->storageImagePathRule(['salon-gallery', 'style-options'], true, ['img-salon']),
            'open_time' => ['nullable', 'date_format:H:i'],
            'close_time' => ['nullable', 'date_format:H:i'],
            'status' => ['sometimes', Rule::in([Salon::STATUS_OPEN, Salon::STATUS_CLOSED])],
            'approval_status' => ['sometimes', Rule::in([
                Salon::APPROVAL_PENDING,
                Salon::APPROVAL_APPROVED,
                Salon::APPROVAL_REJECTED,
            ])],
        ]);
    }
}
