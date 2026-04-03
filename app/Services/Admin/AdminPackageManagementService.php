<?php

namespace App\Services\Admin;

use App\Contracts\Services\Admin\AdminPackageManagementServiceInterface;
use App\Exceptions\BusinessException;
use App\Models\Package;
use App\Repositories\Interfaces\Admin\PackageRepositoryInterface;
use App\Support\AuditLogger;
use App\Support\HtmlSanitizer;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AdminPackageManagementService implements AdminPackageManagementServiceInterface
{
    public function __construct(
        protected PackageRepositoryInterface $packageRepository
    ) {}

    public function listPackages(array $filters): LengthAwarePaginator
    {
        if (! empty($filters['type'])) {
            $filters['type'] = $this->normalizeType($filters['type']);
        }

        return $this->packageRepository->paginate($filters);
    }

    public function createPackage(array $data): Package
    {
        $package = $this->packageRepository->create([
            'name' => $data['name'],
            'type' => $this->normalizeType($data['type']),
            'price' => (int) $data['price'],
            'billing_period' => $this->normalizeBillingPeriod($data['billing_period'] ?? Package::BILLING_1_MONTH),
            'description' => HtmlSanitizer::plainText($data['description'] ?? null),
            'max_staff' => (int) ($data['max_staff'] ?? 10),
            'max_services' => (int) ($data['max_services'] ?? 50),
            'max_bookings_per_month' => (int) ($data['max_bookings_per_month'] ?? 500),
            'is_active' => $data['is_active'] ?? true,
        ]);

        AuditLogger::log('Created package', 'package', $package->id, 'success', [
            'name' => $package->name,
        ]);

        return $package;
    }

    public function updatePackage(string $id, array $data): Package
    {
        $package = $this->findOrFail($id);

        $payload = array_filter([
            'name' => $data['name'] ?? null,
            'type' => isset($data['type']) ? $this->normalizeType($data['type']) : null,
            'price' => isset($data['price']) ? (int) $data['price'] : null,
            'billing_period' => isset($data['billing_period'])
                ? $this->normalizeBillingPeriod($data['billing_period'])
                : null,
            'description' => array_key_exists('description', $data) ? HtmlSanitizer::plainText($data['description']) : null,
            'max_staff' => isset($data['max_staff']) ? (int) $data['max_staff'] : null,
            'max_services' => isset($data['max_services']) ? (int) $data['max_services'] : null,
            'max_bookings_per_month' => isset($data['max_bookings_per_month']) ? (int) $data['max_bookings_per_month'] : null,
            'is_active' => $data['is_active'] ?? null,
        ], fn ($value) => $value !== null);

        $package = $this->packageRepository->update($package, $payload);

        AuditLogger::log('Updated package', 'package', $package->id, 'success', [
            'name' => $package->name,
        ]);

        return $package;
    }

    public function deletePackage(string $id): bool
    {
        $package = $this->findOrFail($id);
        $name = $package->name;
        $deleted = $this->packageRepository->delete($package);

        if ($deleted) {
            AuditLogger::log('Deleted package', 'package', $id, 'success', ['name' => $name]);
        }

        return $deleted;
    }

    protected function normalizeType(string $type): string
    {
        return strtolower(trim($type));
    }

    protected function normalizeBillingPeriod(string $period): string
    {
        $value = strtolower(trim($period));

        return match ($value) {
            Package::BILLING_3_MONTHS, '3_month', '3months' => Package::BILLING_3_MONTHS,
            Package::BILLING_1_YEAR, '1_year', '12_months', '12months', 'year' => Package::BILLING_1_YEAR,
            default => Package::BILLING_1_MONTH,
        };
    }

    protected function findOrFail(string $id): Package
    {
        $package = $this->packageRepository->findById($id);

        if (! $package) {
            throw new BusinessException('Package không tồn tại.', 'PACKAGE_NOT_FOUND', 404);
        }

        return $package;
    }
}
