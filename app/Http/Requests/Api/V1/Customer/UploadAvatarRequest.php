<?php

namespace App\Http\Requests\Api\V1\Customer;

use App\Http\Requests\Concerns\ValidatesStorageImagePaths;
use Illuminate\Foundation\Http\FormRequest;

class UploadAvatarRequest extends FormRequest
{
    use ValidatesStorageImagePaths;

    public function authorize(): bool
    {
        return $this->user()?->isCustomer() ?? false;
    }

    public function rules(): array
    {
        return $this->uploadImageFileRules('avatar');
    }

    public function messages(): array
    {
        return [
            'avatar.required' => 'Vui lòng chọn ảnh đại diện.',
            'avatar.image' => 'Tệp tải lên phải là ảnh.',
            'avatar.max' => 'Ảnh đại diện không được vượt quá 5MB.',
        ];
    }
}
