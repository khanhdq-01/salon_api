<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

final class CustomerAvatarStorage
{
    public static function store(UploadedFile $file, string $userId): string
    {
        ImageUploadStorage::assertAllowedDirectory(ImageUploadStorage::DIR_CUSTOMER_AVATAR);

        $extension = ImageUploadStorage::extensionFromMime($file);
        $filename = $userId.'.'.$extension;
        $relativePath = ImageUploadStorage::DIR_CUSTOMER_AVATAR.'/'.$filename;

        Storage::disk('public')->putFileAs(ImageUploadStorage::DIR_CUSTOMER_AVATAR, $file, $filename);

        return StoragePath::publicUrl($relativePath);
    }

    public static function deleteIfOwned(?string $avatarUrl, string $userId): void
    {
        if (! $avatarUrl) {
            return;
        }

        $relativePath = StoragePath::normalize($avatarUrl);

        if ($relativePath === null || ! str_starts_with($relativePath, ImageUploadStorage::DIR_CUSTOMER_AVATAR.'/')) {
            return;
        }

        $basename = basename($relativePath);

        if (! preg_match('/^'.preg_quote($userId, '/').'\./', $basename)) {
            return;
        }

        Storage::disk('public')->delete($relativePath);
    }
}
