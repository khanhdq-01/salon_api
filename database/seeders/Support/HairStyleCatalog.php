<?php

namespace Database\Seeders\Support;

use Illuminate\Support\Facades\File;

final class HairStyleCatalog
{
    /** @var list<string> */
    public const MEN_STYLE_NAMES = [
        'Layer',
        'Fade',
        'Mohican',
        'Undercut',
        'Side Part',
        'Buzz Cut',
        'Textured Crop',
        'Quiff',
        'Pompadour',
        'Two Block',
        'French Crop',
    ];

    /** @var list<string> */
    public const WOMEN_STYLE_NAMES = [
        'Layer',
        'Wolf Cut',
        'Bob',
        'Pixie',
        'Hime',
        'Long Layer',
        'Curtain Bangs',
        'Butterfly',
        'Shag',
        'Wavy',
        'Medium Layer',
        'Short Bob',
        'Long Straight',
    ];

    /**
     * @return list<string>
     */
    public static function menImagePaths(): array
    {
        return self::resolveImagePaths('men', 'man-hair', count(self::MEN_STYLE_NAMES));
    }

    /**
     * @return list<string>
     */
    public static function womenImagePaths(): array
    {
        return self::resolveImagePaths('woman', 'woman-hair', count(self::WOMEN_STYLE_NAMES));
    }

    /**
     * @return list<string>
     */
    private static function resolveImagePaths(string $folder, string $prefix, int $expectedCount): array
    {
        $directory = database_path("seeders/img-hair/{$folder}");

        if (is_dir($directory)) {
            $paths = collect(File::files($directory))
                ->sortBy(fn ($file) => $file->getFilename())
                ->map(fn ($file) => "img-hair/{$folder}/".$file->getFilename())
                ->values()
                ->all();

            if ($paths !== []) {
                return $paths;
            }
        }

        return collect(range(1, $expectedCount))
            ->map(fn (int $index) => "img-hair/{$folder}/{$prefix}{$index}.png")
            ->all();
    }
}
