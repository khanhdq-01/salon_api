<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

final class StoragePath
{
    /**
     * Normalize a stored image reference to a relative public-disk path.
     * Returns null when the value is empty or not an allowed storage reference.
     */
    public static function normalize(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = trim(str_replace('\\', '/', $value));

        if ($value === '') {
            return null;
        }

        if (str_contains($value, "\0") || preg_match('#(^|/)\.\.(/|$)#', $value)) {
            return null;
        }

        if (preg_match('#^https?://#i', $value)) {
            $appUrl = rtrim((string) config('app.url'), '/');

            if ($appUrl === '' || ! str_starts_with($value, $appUrl.'/storage/')) {
                return null;
            }

            $value = substr($value, strlen($appUrl.'/storage/'));
        } elseif (str_starts_with($value, '/storage/')) {
            $value = substr($value, strlen('/storage/'));
        } elseif (str_starts_with($value, 'storage/')) {
            $value = substr($value, strlen('storage/'));
        }

        $value = ltrim($value, '/');

        return $value === '' ? null : $value;
    }

    /**
     * Demo/seed static asset paths served by the SPA (not Laravel public disk).
     *
     * @param  list<string>  $prefixes
     */
    public static function isLegacyAssetPath(?string $value, array $prefixes): bool
    {
        if ($value === null || trim($value) === '') {
            return true;
        }

        $value = trim(str_replace('\\', '/', $value), '/');

        if ($value === '' || str_contains($value, "\0") || preg_match('#(^|/)\.\.(/|$)#', $value)) {
            return false;
        }

        if (preg_match('#^https?://#i', $value)) {
            return false;
        }

        if (! preg_match('#^[a-zA-Z0-9._/-]+$#', $value)) {
            return false;
        }

        foreach ($prefixes as $prefix) {
            $prefix = trim(str_replace('\\', '/', $prefix), '/');

            if ($prefix !== '' && str_starts_with($value, $prefix.'/')) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param  list<string>|null  $directories
     */
    public static function isValid(?string $value, ?array $directories = null, bool $requireExists = true): bool
    {
        $relative = self::normalize($value);

        if ($relative === null) {
            return $value === null || trim((string) $value) === '';
        }

        if (! self::isAllowedDirectory($relative, $directories)) {
            return false;
        }

        if (! self::hasSafeFilename($relative)) {
            return false;
        }

        if ($requireExists && ! Storage::disk('public')->exists($relative)) {
            return false;
        }

        return true;
    }

    public static function publicUrl(string $relativePath): string
    {
        return '/storage/'.ltrim(str_replace('\\', '/', $relativePath), '/');
    }

    /**
     * @param  list<string>|null  $directories
     */
    private static function isAllowedDirectory(string $relativePath, ?array $directories): bool
    {
        $allowed = $directories ?? config('uploads.directories', []);

        foreach ($allowed as $directory) {
            $directory = trim(str_replace('\\', '/', $directory), '/');

            if ($directory !== '' && str_starts_with($relativePath, $directory.'/')) {
                return true;
            }
        }

        return false;
    }

    private static function hasSafeFilename(string $relativePath): bool
    {
        $filename = basename($relativePath);

        if ($filename === '' || $filename === '.' || $filename === '..') {
            return false;
        }

        return (bool) preg_match('/^[a-zA-Z0-9._-]+$/', $filename);
    }
}
