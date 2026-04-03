<?php

/**
 * Full architectural refactor — moves files and updates all references.
 * Run: php scripts/refactor-architecture.php
 */

declare(strict_types=1);

$basePath = dirname(__DIR__);
$appPath = $basePath . '/app';

/** @return array<string, string> */
function buildFileMoves(string $appPath): array
{
    $moves = [];

    // ── Repository interfaces ──
    $repoInterfaces = [
        'Auth/UserRepositoryInterface.php' => 'Customer',
        'Booking/BookingRepositoryInterface.php' => 'Customer',
        'Payment/PaymentRepositoryInterface.php' => 'Customer',
        'Review/ReviewRepositoryInterface.php' => 'Customer',
        'Salon/SalonRepositoryInterface.php' => 'Owner',
        'Service/ServiceRepositoryInterface.php' => 'Owner',
        'Staff/StaffRepositoryInterface.php' => 'Owner',
    ];
    foreach ($repoInterfaces as $file => $role) {
        $moves["Contracts/Repositories/{$file}"] = "Repositories/Interfaces/{$role}/" . basename($file);
    }

    // ── Repository implementations ──
    $moves['Repositories/BaseRepository.php'] = 'Repositories/Eloquent/BaseRepository.php';
    $moves['Repositories/Concerns/ThrowsNotImplementedRepository.php'] = 'Repositories/Eloquent/Concerns/ThrowsNotImplementedRepository.php';
    $repoImpl = [
        'Auth/UserRepository.php' => 'Customer',
        'Booking/BookingRepository.php' => 'Customer',
        'Payment/PaymentRepository.php' => 'Customer',
        'Review/ReviewRepository.php' => 'Customer',
        'Salon/SalonRepository.php' => 'Owner',
        'Service/ServiceRepository.php' => 'Owner',
        'Staff/StaffRepository.php' => 'Owner',
    ];
    foreach ($repoImpl as $file => $role) {
        $moves["Repositories/{$file}"] = "Repositories/Eloquent/{$role}/" . basename($file);
    }

    // ── Services ──
    $serviceMap = [
        'Auth' => 'Customer',
        'Booking' => 'Customer',
        'Payment' => 'Customer',
        'Review' => 'Customer',
        'Content' => 'Customer',
        'Notification' => 'Customer',
        'Salon' => 'Owner',
        'Service' => 'Owner',
        'Staff' => 'Owner',
        'Subscription' => 'Owner',
        'Concerns' => 'Shared',
    ];
    foreach ($serviceMap as $folder => $role) {
        $dir = $appPath . "/Services/{$folder}";
        if (! is_dir($dir)) {
            continue;
        }
        foreach (glob("{$dir}/*.php") ?: [] as $file) {
            $moves["Services/{$folder}/" . basename($file)] = "Services/{$role}/" . basename($file);
        }
    }

    // ── Service contracts ──
    $contractMap = [
        'Auth' => 'Customer',
        'Booking' => 'Customer',
        'Payment' => 'Customer',
        'Review' => 'Customer',
        'Salon' => 'Owner',
        'Service' => 'Owner',
        'Staff' => 'Owner',
    ];
    foreach ($contractMap as $folder => $role) {
        $dir = $appPath . "/Contracts/Services/{$folder}";
        if (! is_dir($dir)) {
            continue;
        }
        foreach (glob("{$dir}/*.php") ?: [] as $file) {
            $moves["Contracts/Services/{$folder}/" . basename($file)] = "Contracts/Services/{$role}/" . basename($file);
        }
    }

    // ── Controllers ──
    $controllerMap = [
        'Auth' => 'Customer',
        'Favorite' => 'Customer',
        'Content' => 'Customer',
        'Review' => 'Customer',
        'Staff' => 'Owner',
        'Upload' => 'Owner',
    ];
    foreach ($controllerMap as $folder => $role) {
        $dir = $appPath . "/Http/Controllers/Api/V1/{$folder}";
        if (! is_dir($dir)) {
            continue;
        }
        foreach (glob("{$dir}/*.php") ?: [] as $file) {
            $moves["Http/Controllers/Api/V1/{$folder}/" . basename($file)] = "Http/Controllers/Api/V1/{$role}/" . basename($file);
        }
    }

    // Booking — split by role
    $bookingCustomer = [
        'BookingController.php',
        'BookingCancelController.php',
        'BookingRescheduleController.php',
        'BookingAvailableSlotsController.php',
    ];
    foreach ($bookingCustomer as $file) {
        $moves["Http/Controllers/Api/V1/Booking/{$file}"] = "Http/Controllers/Api/V1/Customer/{$file}";
    }
    $moves['Http/Controllers/Api/V1/Booking/BookingStatusController.php'] = 'Http/Controllers/Api/V1/Owner/BookingStatusController.php';

    // Payment — split
    $moves['Http/Controllers/Api/V1/Payment/PaymentController.php'] = 'Http/Controllers/Api/V1/Customer/PaymentController.php';
    $moves['Http/Controllers/Api/V1/Payment/PaymentCallbackController.php'] = 'Http/Controllers/Api/V1/Customer/PaymentCallbackController.php';
    $moves['Http/Controllers/Api/V1/Payment/PaymentRefundController.php'] = 'Http/Controllers/Api/V1/Owner/PaymentRefundController.php';

    // Salon — split
    $moves['Http/Controllers/Api/V1/Salon/SalonController.php'] = 'Http/Controllers/Api/V1/Owner/SalonController.php';
    $moves['Http/Controllers/Api/V1/Salon/SalonOwnerController.php'] = 'Http/Controllers/Api/V1/Owner/SalonOwnerController.php';
    $moves['Http/Controllers/Api/V1/Salon/SalonStatusController.php'] = 'Http/Controllers/Api/V1/Owner/SalonStatusController.php';
    $moves['Http/Controllers/Api/V1/Salon/SalonSearchController.php'] = 'Http/Controllers/Api/V1/Customer/SalonSearchController.php';
    $moves['Http/Controllers/Api/V1/Salon/SalonHairstyleController.php'] = 'Http/Controllers/Api/V1/Customer/SalonHairstyleController.php';

    // Service — split
    $moves['Http/Controllers/Api/V1/Service/ServiceController.php'] = 'Http/Controllers/Api/V1/Owner/ServiceController.php';
    $moves['Http/Controllers/Api/V1/Service/StyleOptionController.php'] = 'Http/Controllers/Api/V1/Owner/StyleOptionController.php';
    $moves['Http/Controllers/Api/V1/Service/PopularServiceController.php'] = 'Http/Controllers/Api/V1/Customer/PopularServiceController.php';
    $moves['Http/Controllers/Api/V1/Service/ServiceSearchController.php'] = 'Http/Controllers/Api/V1/Customer/ServiceSearchController.php';

    // Notification — split
    $moves['Http/Controllers/Api/V1/Notification/CustomerNotificationController.php'] = 'Http/Controllers/Api/V1/Customer/CustomerNotificationController.php';
    $moves['Http/Controllers/Api/V1/Notification/OwnerNotificationController.php'] = 'Http/Controllers/Api/V1/Owner/OwnerNotificationController.php';

    // ── Requests ──
    $requestMap = [
        'Auth' => 'Customer',
        'Booking' => 'Customer',
        'Review' => 'Customer',
        'Payment' => 'Customer',
        'Profile' => 'Customer',
        'Admin' => 'Admin',
        'Owner' => 'Owner',
        'Staff' => 'Owner',
        'Upload' => 'Owner',
    ];
    foreach ($requestMap as $folder => $role) {
        $dir = $appPath . "/Http/Requests/{$folder}";
        if (! is_dir($dir)) {
            continue;
        }
        foreach (glob("{$dir}/*.php") ?: [] as $file) {
            $moves["Http/Requests/{$folder}/" . basename($file)] = "Http/Requests/Api/V1/{$role}/" . basename($file);
        }
    }
    $moves['Http/Requests/Salon/ListSalonRequest.php'] = 'Http/Requests/Api/V1/Customer/ListSalonRequest.php';
    $salonOwnerRequests = ['StoreSalonRequest.php', 'UpdateSalonRequest.php', 'UpdateSalonStatusRequest.php'];
    foreach ($salonOwnerRequests as $file) {
        $moves["Http/Requests/Salon/{$file}"] = "Http/Requests/Api/V1/Owner/{$file}";
    }
    $moves['Http/Requests/Notification/BroadcastNotificationRequest.php'] = 'Http/Requests/Api/V1/Owner/BroadcastNotificationRequest.php';
    $customerServiceRequests = ['ListServiceRequest.php', 'SearchServiceRequest.php'];
    $ownerServiceRequests = ['StoreServiceRequest.php', 'UpdateServiceRequest.php', 'StoreStyleOptionRequest.php', 'UpdateStyleOptionRequest.php', 'ListStyleOptionRequest.php'];
    foreach ($customerServiceRequests as $file) {
        $moves["Http/Requests/Service/{$file}"] = "Http/Requests/Api/V1/Customer/{$file}";
    }
    foreach ($ownerServiceRequests as $file) {
        $moves["Http/Requests/Service/{$file}"] = "Http/Requests/Api/V1/Owner/{$file}";
    }

    // ── Resources ──
    $resourceMap = [
        'Auth' => 'Customer',
        'Booking' => 'Customer',
        'Payment' => 'Customer',
        'Review' => 'Customer',
        'Content' => 'Customer',
        'Admin' => 'Admin',
        'Owner' => 'Owner',
        'Staff' => 'Owner',
    ];
    foreach ($resourceMap as $folder => $role) {
        $dir = $appPath . "/Http/Resources/{$folder}";
        if (! is_dir($dir)) {
            continue;
        }
        foreach (glob("{$dir}/*.php") ?: [] as $file) {
            $moves["Http/Resources/{$folder}/" . basename($file)] = "Http/Resources/Api/V1/{$role}/" . basename($file);
        }
    }
    $salonResources = [
        'SalonResource.php' => 'Customer',
        'SalonCollection.php' => 'Customer',
        'SalonHairstyleResource.php' => 'Customer',
        'SalonOwnerResource.php' => 'Owner',
    ];
    foreach ($salonResources as $file => $role) {
        $moves["Http/Resources/Salon/{$file}"] = "Http/Resources/Api/V1/{$role}/{$file}";
    }
    $serviceResources = [
        'PopularServiceResource.php' => 'Customer',
        'ServiceResource.php' => 'Owner',
        'ServiceStyleOptionResource.php' => 'Owner',
    ];
    foreach ($serviceResources as $file => $role) {
        $moves["Http/Resources/Service/{$file}"] = "Http/Resources/Api/V1/{$role}/{$file}";
    }
    $moves['Http/Resources/Notification/CustomerNotificationResource.php'] = 'Http/Resources/Api/V1/Customer/CustomerNotificationResource.php';
    $moves['Http/Resources/Notification/OwnerNotificationBroadcastResource.php'] = 'Http/Resources/Api/V1/Owner/OwnerNotificationBroadcastResource.php';

    return $moves;
}

/** @return array<string, string> Old FQCN => New FQCN */
function buildNamespaceMap(array $fileMoves): array
{
    $map = [];

    foreach ($fileMoves as $from => $to) {
        $oldNs = pathToNamespace($from);
        $newNs = pathToNamespace($to);
        if ($oldNs !== $newNs) {
            $map[$oldNs] = $newNs;
        }
    }

    // Sort longest first to avoid partial replacements
    uksort($map, static fn ($a, $b) => strlen($b) <=> strlen($a));

    return $map;
}

function pathToNamespace(string $relativePath): string
{
    $path = preg_replace('/\.php$/', '', $relativePath);
    $path = str_replace('/', '\\', $path);

    $prefixMap = [
        'Contracts\\Repositories' => 'App\\Contracts\\Repositories',
        'Contracts\\Services' => 'App\\Contracts\\Services',
        'Repositories\\Interfaces' => 'App\\Repositories\\Interfaces',
        'Repositories\\Eloquent' => 'App\\Repositories\\Eloquent',
        'Repositories\\' => 'App\\Repositories\\',
        'Services\\' => 'App\\Services\\',
        'Http\\Controllers\\' => 'App\\Http\\Controllers\\',
        'Http\\Requests\\' => 'App\\Http\\Requests\\',
        'Http\\Resources\\' => 'App\\Http\\Resources\\',
    ];

    foreach ($prefixMap as $prefix => $fullPrefix) {
        if (str_starts_with($path, $prefix)) {
            return $fullPrefix . substr($path, strlen($prefix));
        }
    }

    return 'App\\' . $path;
}

function ensureDirectory(string $path): void
{
    if (! is_dir($path)) {
        mkdir($path, 0755, true);
    }
}

function moveFile(string $appPath, string $from, string $to): bool
{
    $source = "{$appPath}/{$from}";
    $dest = "{$appPath}/{$to}";

    if (! file_exists($source)) {
        echo "SKIP (missing): {$from}\n";
        return false;
    }

    if (file_exists($dest)) {
        echo "SKIP (exists): {$to}\n";
        return false;
    }

    ensureDirectory(dirname($dest));
    rename($source, $dest);
    echo "MOVED: {$from} -> {$to}\n";

    return true;
}

function updateFileNamespace(string $filePath, string $newNamespace): void
{
    $content = file_get_contents($filePath);
    $updated = preg_replace(
        '/^namespace\s+[^;]+;/m',
        "namespace {$newNamespace};",
        $content,
        1
    );
    file_put_contents($filePath, $updated);
}

function replaceInPhpFiles(string $directory, array $replacements): int
{
    $count = 0;
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));

    foreach ($iterator as $file) {
        if (! $file->isFile() || $file->getExtension() !== 'php') {
            continue;
        }

        $path = $file->getPathname();
        $content = file_get_contents($path);
        $original = $content;

        foreach ($replacements as $old => $new) {
            $content = str_replace($old, $new, $content);
        }

        if ($content !== $original) {
            file_put_contents($path, $content);
            $count++;
        }
    }

    return $count;
}

function cleanupEmptyDirs(string $dir): void
{
    if (! is_dir($dir)) {
        return;
    }

    foreach (glob("{$dir}/*") ?: [] as $item) {
        if (is_dir($item)) {
            cleanupEmptyDirs($item);
        }
    }

    if (count(glob("{$dir}/*") ?: []) === 0) {
        @rmdir($dir);
    }
}

// ── Execute ──
echo "Building move map...\n";
$fileMoves = buildFileMoves($appPath);
echo count($fileMoves) . " files to move.\n\n";

echo "=== Phase 1: Move files ===\n";
$moved = [];
foreach ($fileMoves as $from => $to) {
    if (moveFile($appPath, $from, $to)) {
        $moved[$from] = $to;
        $newNs = pathToNamespace($to);
        updateFileNamespace("{$appPath}/{$to}", $newNs);
    }
}

echo "\n=== Phase 2: Build namespace replacement map ===\n";
$namespaceMap = buildNamespaceMap($fileMoves);
echo count($namespaceMap) . " namespace mappings.\n";

echo "\n=== Phase 3: Global reference update ===\n";
$scanDirs = [
    $appPath,
    $basePath . '/routes',
    $basePath . '/tests',
    $basePath . '/database',
];
$total = 0;
foreach ($scanDirs as $dir) {
    if (is_dir($dir)) {
        $total += replaceInPhpFiles($dir, $namespaceMap);
    }
}
echo "Updated {$total} files.\n";

echo "\n=== Phase 4: Cleanup empty directories ===\n";
cleanupEmptyDirs($appPath);

echo "\nRefactor script completed.\n";
