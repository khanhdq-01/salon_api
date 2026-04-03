<?php

namespace App\Http\Requests\Api\V1\Owner;

use App\Http\Requests\Concerns\ValidatesStorageImagePaths;
use Illuminate\Foundation\Http\FormRequest;

class UploadStyleOptionImageRequest extends FormRequest
{
    use ValidatesStorageImagePaths;

    public function authorize(): bool
    {
        return $this->user()?->isOwner() || $this->user()?->isAdmin();
    }

    public function rules(): array
    {
        return $this->uploadImageFileRules('image');
    }
}
