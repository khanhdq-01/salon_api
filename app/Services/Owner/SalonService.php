<?php

namespace App\Services\Owner;

use App\Contracts\Services\Owner\SalonServiceInterface;
use App\Exceptions\BusinessException;
use App\Models\Package;
use App\Models\Salon;
use App\Models\User;
use App\Repositories\Interfaces\Owner\PackageRepositoryInterface;
use App\Repositories\Interfaces\Owner\SalonRepositoryInterface;
use App\Support\AvailableSlotsCache;
use App\Support\SalonMapper;
use App\Support\SubscriptionExpiry;
use App\Support\TrialPackage;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SalonService implements SalonServiceInterface
{
    private const LIST_CACHE_TTL = 300;

    public function __construct(
        protected SalonRepositoryInterface $salonRepository,
        protected SalonTodayAvailabilityService $todayAvailabilityService,
        protected PackageRepositoryInterface $packageRepository,
    ) {}

    public function createSalon(array $data, User $actor): Salon
    {
        if ($actor->isAdmin()) {
            throw new BusinessException('Admin không có quyền tạo salon.', 'FORBIDDEN', 403);
        }

        if (! $actor->isOwner()) {
            throw new BusinessException('Forbidden.', 'FORBIDDEN', 403);
        }

        unset($data['approval_status']);
        $data['owner_id'] = $actor->id;

        $package = $this->resolveActivePackage($data['package_id'] ?? null);
        TrialPackage::assertOwnerCanSelectTrial($actor->id, $package);
        unset($data['package_id']);

        $payload = SalonMapper::normalizeCreate($data);
        $payload['requested_package_id'] = $package->id;
        $payload['approval_status'] = Salon::APPROVAL_PENDING;
        $this->assertOwnerCanCreate($payload['owner_id'], $actor);

        return DB::transaction(function () use ($payload) {
            $existing = $this->salonRepository->findByOwnerId($payload['owner_id'], withTrashed: true);

            if ($existing) {
                if ($existing->trashed()) {
                    throw new BusinessException(
                        'Salon của owner đã bị xóa. Vui lòng liên hệ Admin để khôi phục.',
                        'OWNER_SALON_DELETED',
                        409
                    );
                }

                throw new BusinessException(
                    'Owner đã có salon. MVP chỉ hỗ trợ 1 salon/owner.',
                    'OWNER_SALON_EXISTS',
                    409
                );
            }

            try {
                $salon = $this->salonRepository->create($payload);
            } catch (QueryException $e) {
                if ($this->isDuplicateOwnerSalon($e)) {
                    throw new BusinessException(
                        'Owner đã có salon. MVP chỉ hỗ trợ 1 salon/owner.',
                        'OWNER_SALON_EXISTS',
                        409
                    );
                }

                throw $e;
            }

            if (! empty($payload['image_url'])) {
                $salon->images()->create([
                    'image_url' => $payload['image_url'],
                ]);
            }

            app(\App\Contracts\Services\Owner\OwnerSalonSettingsServiceInterface::class)
                ->ensureForSalon($salon->id);

            $this->flushListCache();

            return $salon->load(['owner:id,name,email,phone', 'requestedPackage', 'images']);
        });
    }

    public function updateSalon(string $id, array $data, User $actor): Salon
    {
        $salon = $this->findOrFail($id);
        $payload = SalonMapper::normalizeUpdate($data);

        if ($actor->isOwner() && ! $actor->isAdmin() && $salon->approval_status === Salon::APPROVAL_REJECTED) {
            $payload['approval_status'] = Salon::APPROVAL_PENDING;
        }

        if (! empty($payload['open_time']) && ! empty($payload['close_time'])) {
            $this->assertValidOpeningHours($payload['open_time'], $payload['close_time']);
        }

        $shouldInvalidateSlots = isset($payload['open_time']) || isset($payload['close_time']);

        $updated = $this->salonRepository->update($salon, $payload);
        $this->flushListCache();

        if ($shouldInvalidateSlots) {
            AvailableSlotsCache::forgetSalonWide($updated->id);
        }

        return $updated;
    }

    public function updateSalonStatus(string $id, array $data, User $actor): Salon
    {
        $salon = $this->findOrFail($id);
        $payload = SalonMapper::normalizeStatusUpdate($data);

        if (! $actor->isAdmin()) {
            unset($payload['approval_status'], $payload['is_locked']);

            if (empty($payload)) {
                throw new BusinessException(
                    'Owner chỉ được cập nhật trạng thái vận hành (open/closed).',
                    'FORBIDDEN_STATUS_FIELD'
                );
            }
        }

        if (isset($payload['status'])) {
            $this->assertValidOperationalStatus($payload['status']);
        }

        if (isset($payload['approval_status'])) {
            $this->assertValidApprovalStatus($payload['approval_status']);
        }

        $updated = $this->salonRepository->update($salon, $payload);
        $this->flushListCache();

        if (isset($payload['status']) || isset($payload['approval_status']) || isset($payload['is_locked'])) {
            AvailableSlotsCache::forgetSalonWide($updated->id);
        }

        return $updated;
    }

    public function deleteSalon(string $id, User $actor): bool
    {
        $salon = $this->findOrFail($id);
        $deleted = $this->salonRepository->delete($salon);
        $this->flushListCache();

        return $deleted;
    }

    public function restoreSalon(string $id, User $actor): Salon
    {
        if (! $actor->isAdmin()) {
            throw new BusinessException('Forbidden.', 'FORBIDDEN', 403);
        }

        $salon = $this->findOrFail($id, ['owner:id,name,email,phone'], withTrashed: true);

        if (! $salon->trashed()) {
            throw new BusinessException('Salon chưa bị xóa.', 'SALON_NOT_DELETED', 422);
        }

        $salon->restore();
        $this->flushListCache();

        return $salon->fresh(['owner:id,name,email,phone']);
    }

    public function getSalonById(string $id, ?User $actor = null): Salon
    {
        $withTrashed = $actor?->isAdmin() ?? false;
        $salon = $this->findOrFail($id, ['owner:id,name,email,phone,status'], $withTrashed);

        if ($this->shouldRestrictToPublic($actor) && ! $salon->isVisibleToPublic()) {
            throw new BusinessException('Salon không khả dụng.', 'SALON_NOT_AVAILABLE', 404);
        }

        return $salon->load(['images' => fn ($query) => $query->orderByDesc('created_at')]);
    }

    public function listSalons(array $filters, ?User $actor = null): LengthAwarePaginator
    {
        $filters = SalonMapper::normalizeListFilters($filters);
        $filters = $this->resolveListFilters($filters, $actor);

        if ($filters['public_only'] ?? false) {
            SubscriptionExpiry::syncExpiredSubscriptions();
        }

        if ($filters['available_today'] ?? false) {
            $filters['available_salon_ids'] = $this->todayAvailabilityService->getAvailableSalonIds();
        }

        if ($this->canUseListCache($filters)) {
            $version = Cache::get('salons:list:version', 1);
            $cacheKey = 'salons:list:' . $version . ':' . md5(serialize($filters));

            return Cache::remember($cacheKey, self::LIST_CACHE_TTL, fn () => $this->salonRepository->paginate($filters));
        }

        return $this->salonRepository->paginate($filters);
    }

    public function findSalonOrFail(string $id, ?User $actor = null): Salon
    {
        $withTrashed = $actor?->isAdmin() ?? false;

        return $this->findOrFail($id, ['owner:id,name,email,phone'], $withTrashed);
    }

    public function getOwnerSalon(User $owner): Salon
    {
        $salon = $this->salonRepository->findByOwnerId($owner->id);

        if (! $salon) {
            throw new BusinessException('Owner chưa có salon.', 'OWNER_SALON_NOT_FOUND', 404);
        }

        return $salon->load(['owner:id,name,email,phone', 'images' => fn ($query) => $query->orderByDesc('created_at')]);
    }

    protected function findOrFail(string $id, array $relations = [], bool $withTrashed = false): Salon
    {
        $salon = $this->salonRepository->findById($id, $relations, $withTrashed);

        if (! $salon) {
            throw new BusinessException('Salon không tồn tại.', 'SALON_NOT_FOUND', 404);
        }

        return $salon;
    }

    protected function assertOwnerCanCreate(string $ownerId, User $actor): void
    {
        if ($actor->isOwner() && $actor->id === $ownerId) {
            return;
        }

        throw new BusinessException('Không có quyền tạo salon.', 'FORBIDDEN', 403);
    }

    protected function assertValidOpeningHours(string $open, string $close): void
    {
        if ($open >= $close) {
            throw new BusinessException('Giờ mở cửa phải trước giờ đóng cửa.', 'INVALID_OPENING_HOURS');
        }
    }

    protected function assertValidOperationalStatus(string $status): void
    {
        if (! in_array($status, [Salon::STATUS_OPEN, Salon::STATUS_CLOSED], true)) {
            throw new BusinessException('Status không hợp lệ.', 'INVALID_STATUS');
        }
    }

    protected function assertValidApprovalStatus(string $status): void
    {
        if (! in_array($status, [Salon::APPROVAL_PENDING, Salon::APPROVAL_APPROVED, Salon::APPROVAL_REJECTED], true)) {
            throw new BusinessException('Approval status không hợp lệ.', 'INVALID_APPROVAL_STATUS');
        }
    }

    protected function shouldRestrictToPublic(?User $actor): bool
    {
        return ! $actor || $actor->isCustomer();
    }

    protected function resolveListFilters(array $filters, ?User $actor): array
    {
        if ($actor?->isAdmin()) {
            $filters['public_only'] = false;
            $filters['with_trashed'] = true;

            return $filters;
        }

        if ($actor?->isOwner()) {
            $filters['public_only'] = false;
            $filters['owner_id'] = $actor->id;

            return $filters;
        }

        return $filters;
    }

    protected function canUseListCache(array $filters): bool
    {
        return ($filters['public_only'] ?? false)
            && empty($filters['query'])
            && empty($filters['owner_id'])
            && $filters['lat'] === null
            && $filters['lng'] === null
            && $filters['distance_km'] === null
            && $filters['rating_min'] === null
            && ! ($filters['available_today'] ?? false);
    }

    protected function flushListCache(): void
    {
        Cache::increment('salons:list:version');
    }

    protected function isDuplicateOwnerSalon(QueryException $e): bool
    {
        $errorCode = (int) ($e->errorInfo[1] ?? 0);

        if ($errorCode !== 1062) {
            return false;
        }

        $message = strtolower($e->getMessage());

        return str_contains($message, 'salons_owner_id_unique') || str_contains($message, 'owner_id');
    }

    protected function resolveActivePackage(?string $packageId): Package
    {
        if (! $packageId) {
            throw new BusinessException('Vui lòng chọn gói dịch vụ.', 'PACKAGE_REQUIRED', 422);
        }

        $package = $this->packageRepository->findActiveById($packageId);

        if (! $package) {
            throw new BusinessException('Gói dịch vụ không khả dụng.', 'PACKAGE_NOT_FOUND', 422);
        }

        return $package;
    }
}
