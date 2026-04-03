<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use InvalidArgumentException;

final class ImageUploadStorage
{
    public const DIR_STYLE_OPTIONS = 'style-options';

    public const DIR_SALON_GALLERY = 'salon-gallery';

    public const DIR_SUBSCRIPTION_PAYMENTS = 'subscription-payments';

    public const DIR_CUSTOMER_AVATAR = 'avt-customer';

    /**
     * @return array{path: string, url: string}
     */
    public static function store(UploadedFile $file, string $directory): array
    {
        self::assertAllowedDirectory($directory);

        $path = str_replace('\\', '/', $file->store($directory, 'public'));

        return [
            'path' => $path,
            'url' => StoragePath::publicUrl($path),
        ];
    }

    /**
     * @return array<int, string|\Illuminate\Contracts\Validation\ValidationRule>
     */
    public static function fileRules(string $field = 'image', bool $required = true): array
    {
        $mimes = implode(',', config('uploads.allowed_mimes', ['jpeg', 'jpg', 'png', 'webp', 'gif']));
        $max = (int) config('uploads.max_size_kb', 5120);

        $rules = ['file', 'image', 'mimes:'.$mimes, 'max:'.$max];

        array_unshift($rules, $required ? 'required' : 'nullable');

        return [$field => $rules];
    }

    public static function extensionFromMime(UploadedFile $file): string
    {
        $mime = strtolower((string) $file->getMimeType());

        return match ($mime) {
            'image/jpeg', 'image/jpg', 'image/pjpeg' => 'jpg',
            'image/png', 'image/x-png' => 'png',
            'image/webp' => 'webp',
            'image/gif' => 'gif',
            default => 'jpg',
        };
    }

    public static function assertAllowedDirectory(string $directory): void
    {
        $directory = trim(str_replace('\\', '/', $directory), '/');
        $allowed = config('uploads.directories', []);

        if (! in_array($directory, $allowed, true)) {
            throw new InvalidArgumentException("Upload directory [{$directory}] is not allowed.");
        }
    }
}
