<?php

namespace App\Http\Requests\Concerns;

use App\Rules\StorageImagePath;
use App\Support\ImageUploadStorage;

trait ValidatesStorageImagePaths
{
    /**
     * @param  list<string>|null  $directories
     * @param  list<string>  $legacyPrefixes
     * @return list<string|\Illuminate\Contracts\Validation\ValidationRule>
     */
    protected function storageImagePathRule(
        ?array $directories = null,
        bool $requireExists = true,
        array $legacyPrefixes = [],
    ): array {
        return ['nullable', 'string', 'max:500', new StorageImagePath($directories, $requireExists, $legacyPrefixes)];
    }

    /**
     * @return array<string, list<string>>
     */
    protected function uploadImageFileRules(string $field = 'image', bool $required = true): array
    {
        return ImageUploadStorage::fileRules($field, $required);
    }
}
