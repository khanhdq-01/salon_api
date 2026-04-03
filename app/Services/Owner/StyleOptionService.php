<?php

namespace App\Services\Owner;

use App\Exceptions\BusinessException;
use App\Models\Service;
use App\Models\ServiceStyleOption;
use App\Models\User;
use App\Repositories\Interfaces\Owner\SalonRepositoryInterface;
use App\Repositories\Interfaces\Owner\ServiceRepositoryInterface;
use App\Repositories\Interfaces\Owner\ServiceStyleOptionRepositoryInterface;
use App\Support\HtmlSanitizer;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class StyleOptionService
{
    public function __construct(
        protected ServiceStyleOptionRepositoryInterface $styleOptionRepository,
        protected ServiceRepositoryInterface $serviceRepository,
        protected SalonRepositoryInterface $salonRepository,
    ) {}

    /**
     * @return Collection<int, ServiceStyleOption>
     */
    public function listBySalon(string $salonId, User $user): Collection
    {
        $this->assertSalonAccess($salonId, $user);

        return $this->styleOptionRepository->listBySalon($salonId);
    }

    public function create(array $data, User $user): ServiceStyleOption
    {
        $service = $this->resolveService((string) $data['service_id'], $user);

        $sortOrder = $data['sort_order']
            ?? ((int) $service->styleOptions()->max('sort_order')) + 1;

        return $this->styleOptionRepository->create([
            'id' => (string) Str::uuid(),
            'service_id' => $service->id,
            'name' => trim((string) $data['name']),
            'gender' => $data['gender'] ?? 'unisex',
            'description' => HtmlSanitizer::plainText($data['description'] ?? null),
            'article' => HtmlSanitizer::richHtml($data['article'] ?? null),
            'extra_price' => max(0, (int) ($data['extra_price'] ?? 0)),
            'extra_duration' => max(0, (int) ($data['extra_duration'] ?? 0)),
            'image' => filled($data['image'] ?? null) ? (string) $data['image'] : null,
            'sort_order' => max(0, (int) $sortOrder),
            'is_active' => array_key_exists('is_active', $data) ? (bool) $data['is_active'] : true,
            'is_featured' => (bool) ($data['is_featured'] ?? false),
        ])->load(['service:id,name,salon_id']);
    }

    public function update(string $id, array $data, User $user): ServiceStyleOption
    {
        $styleOption = $this->findOrFail($id);
        $this->assertStyleOptionAccess($styleOption, $user);

        $payload = [];

        if (array_key_exists('service_id', $data)) {
            $service = $this->resolveService((string) $data['service_id'], $user);
            $payload['service_id'] = $service->id;
        }

        if (array_key_exists('name', $data)) {
            $payload['name'] = trim((string) $data['name']);
        }

        if (array_key_exists('gender', $data)) {
            $payload['gender'] = $data['gender'] ?? 'unisex';
        }

        if (array_key_exists('description', $data)) {
            $payload['description'] = HtmlSanitizer::plainText($data['description'] ?? null);
        }

        if (array_key_exists('article', $data)) {
            $payload['article'] = HtmlSanitizer::richHtml($data['article'] ?? null);
        }

        if (array_key_exists('extra_price', $data)) {
            $payload['extra_price'] = max(0, (int) $data['extra_price']);
        }

        if (array_key_exists('extra_duration', $data)) {
            $payload['extra_duration'] = max(0, (int) $data['extra_duration']);
        }

        if (array_key_exists('image', $data)) {
            $payload['image'] = filled($data['image']) ? (string) $data['image'] : null;
        }

        if (array_key_exists('sort_order', $data)) {
            $payload['sort_order'] = max(0, (int) $data['sort_order']);
        }

        if (array_key_exists('is_active', $data)) {
            $payload['is_active'] = (bool) $data['is_active'];
        }

        if (array_key_exists('is_featured', $data)) {
            $payload['is_featured'] = (bool) $data['is_featured'];
        }

        $styleOption->update($payload);

        return $styleOption->fresh(['service:id,name,salon_id']);
    }

    public function delete(string $id, User $user): void
    {
        $styleOption = $this->findOrFail($id);
        $this->assertStyleOptionAccess($styleOption, $user);
        $styleOption->delete();
    }

    public function findOrFail(string $id): ServiceStyleOption
    {
        $styleOption = $this->styleOptionRepository->findWithService($id);

        if (! $styleOption) {
            throw new BusinessException('Không tìm thấy hair style option.', 404, 'STYLE_OPTION_NOT_FOUND');
        }

        return $styleOption;
    }

    protected function resolveService(string $serviceId, User $user): Service
    {
        $service = $this->serviceRepository->findById($serviceId, ['salon:id,owner_id']);

        if (! $service) {
            throw new BusinessException('Không tìm thấy dịch vụ.', 404, 'SERVICE_NOT_FOUND');
        }

        if ($user->isAdmin()) {
            return $service;
        }

        if (! $user->isOwner() || $service->salon?->owner_id !== $user->id) {
            throw new BusinessException('Bạn không có quyền thao tác dịch vụ này.', 403, 'FORBIDDEN');
        }

        return $service;
    }

    protected function assertSalonAccess(string $salonId, User $user): void
    {
        if ($user->isAdmin()) {
            return;
        }

        $salon = $this->salonRepository->findById($salonId);

        if (! $salon || $salon->owner_id !== $user->id) {
            throw new BusinessException('Bạn không có quyền xem hair style options của salon này.', 403, 'FORBIDDEN');
        }
    }

    protected function assertStyleOptionAccess(ServiceStyleOption $styleOption, User $user): void
    {
        if ($user->isAdmin()) {
            return;
        }

        if (! $user->isOwner() || $styleOption->service?->salon?->owner_id !== $user->id) {
            throw new BusinessException('Bạn không có quyền thao tác hair style option này.', 403, 'FORBIDDEN');
        }
    }
}
