<?php

namespace Tests\Unit\Support;

use App\Support\ImageUploadStorage;
use App\Support\StoragePath;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class StoragePathTest extends TestCase
{
    public function test_normalize_relative_path(): void
    {
        $this->assertSame(
            'style-options/abc.jpg',
            StoragePath::normalize('style-options/abc.jpg')
        );
    }

    public function test_normalize_storage_prefix(): void
    {
        $this->assertSame(
            'salon-gallery/photo.png',
            StoragePath::normalize('/storage/salon-gallery/photo.png')
        );
    }

    public function test_rejects_external_url(): void
    {
        $this->assertNull(StoragePath::normalize('https://evil.example/image.png'));
    }

    public function test_rejects_path_traversal(): void
    {
        $this->assertNull(StoragePath::normalize('../style-options/abc.jpg'));
    }

    public function test_public_url(): void
    {
        $this->assertSame(
            '/storage/style-options/abc.jpg',
            StoragePath::publicUrl('style-options/abc.jpg')
        );
    }

    public function test_extension_from_mime_ignores_client_extension(): void
    {
        $file = UploadedFile::fake()->create('avatar.php', 100, 'image/jpeg');

        $this->assertSame('jpg', ImageUploadStorage::extensionFromMime($file));
    }

    public function test_legacy_asset_path_is_allowed(): void
    {
        $this->assertTrue(StoragePath::isLegacyAssetPath('img-salon/salon1.png', ['img-salon']));
        $this->assertFalse(StoragePath::isLegacyAssetPath('https://evil.example/x.png', ['img-salon']));
    }
}
