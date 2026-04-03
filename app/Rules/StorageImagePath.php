<?php

namespace App\Rules;

use App\Support\StoragePath;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class StorageImagePath implements ValidationRule
{
    /**
     * @param  list<string>|null  $directories
     * @param  list<string>  $legacyPrefixes
     */
    public function __construct(
        private readonly ?array $directories = null,
        private readonly bool $requireExists = true,
        private readonly array $legacyPrefixes = [],
    ) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value === null || $value === '') {
            return;
        }

        if (! is_string($value)) {
            $fail('Đường dẫn ảnh không hợp lệ.');

            return;
        }

        if (StoragePath::isLegacyAssetPath($value, $this->legacyPrefixes)) {
            return;
        }

        if (! StoragePath::isValid($value, $this->directories, $this->requireExists)) {
            $fail('Đường dẫn ảnh phải trỏ tới file đã tải lên trên hệ thống.');
        }
    }
}
