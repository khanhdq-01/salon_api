<?php

/**
 * Fix namespace declarations — namespace must be directory, not class name.
 * Run: php scripts/fix-namespaces.php
 */

declare(strict_types=1);

$basePath = dirname(__DIR__);
$appPath = $basePath . '/app';

function expectedNamespace(string $relativePath): string
{
    $dir = dirname(str_replace('/', DIRECTORY_SEPARATOR, $relativePath));
    $dir = str_replace(DIRECTORY_SEPARATOR, '\\', $dir);

    return 'App\\' . $dir;
}

function scanPhpFiles(string $directory): array
{
    $files = [];
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));

    foreach ($iterator as $file) {
        if ($file->isFile() && $file->getExtension() === 'php') {
            $files[] = $file->getPathname();
        }
    }

    return $files;
}

$fixed = 0;

foreach (scanPhpFiles($appPath) as $fullPath) {
    $relative = str_replace('\\', '/', substr($fullPath, strlen($appPath) + 1));
    $expected = expectedNamespace($relative);

    $content = file_get_contents($fullPath);
    if (! preg_match('/^namespace\s+([^;]+);/m', $content, $matches)) {
        continue;
    }

    $current = trim($matches[1]);
    if ($current === $expected) {
        continue;
    }

    $updated = preg_replace(
        '/^namespace\s+[^;]+;/m',
        "namespace {$expected};",
        $content,
        1
    );
    file_put_contents($fullPath, $updated);
    echo "FIXED: {$relative}\n  {$current} -> {$expected}\n";
    $fixed++;
}

echo "\nFixed {$fixed} files.\n";
