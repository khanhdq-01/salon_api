<?php

namespace App\Http\Requests\Api\V1\Admin;

use App\Http\Requests\Concerns\ValidatesStorageImagePaths;
use Illuminate\Foundation\Http\FormRequest;

class UpdateAdminSettingsRequest extends FormRequest
{
    use ValidatesStorageImagePaths;
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'system_name' => ['sometimes', 'string', 'max:200'],
            'logo_url' => ['nullable', 'string', 'max:500'],
            'support_email' => ['sometimes', 'email', 'max:200'],
            'support_phone' => ['nullable', 'string', 'max:50'],
            'timezone' => ['sometimes', 'string', 'max:100'],
            'language' => ['sometimes', 'string', 'max:10'],
            'currency' => ['sometimes', 'string', 'max:10'],
            'email_sender_name' => ['nullable', 'string', 'max:200'],
            'email_sender_address' => ['nullable', 'email', 'max:200'],
            'enable_notifications' => ['nullable', 'boolean'],
            'app_qr_url' => $this->storageImagePathRule(['style-options']),
            'app_image_url' => $this->storageImagePathRule(['style-options']),
            'app_image_url_2' => $this->storageImagePathRule(['style-options']),
            'app_description' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
