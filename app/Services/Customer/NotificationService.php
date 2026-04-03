<?php

namespace App\Services\Customer;

use App\Jobs\DispatchNotificationBroadcastJob;
use App\Contracts\Services\Owner\SalonServiceInterface;
use App\Exceptions\BusinessException;
use App\Models\OwnerNotificationBroadcast;
use App\Models\Salon;
use App\Models\User;
use App\Repositories\Interfaces\Customer\NotificationRepositoryInterface;
use App\Support\NotificationTypes;
use App\Support\HtmlSanitizer;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class NotificationService
{
    public function __construct(
        protected SalonServiceInterface $salonService,
        protected NotificationRepositoryInterface $notificationRepository,
    ) {}

    public function broadcast(User $owner, string $title, string $content, string $type = NotificationTypes::GENERAL, ?string $scheduledAt = null): OwnerNotificationBroadcast
    {
        return $this->createNotification($owner, [
            'title' => HtmlSanitizer::plainText($title) ?? '',
            'content' => HtmlSanitizer::richHtml($content) ?? '',
            'type' => $type,
            'scheduled_at' => $scheduledAt,
        ]);
    }

    /**
     * @param  array{title: string, content: string, type: string, scheduled_at?: string|null}  $data
     */
    public function createNotification(User $owner, array $data): OwnerNotificationBroadcast
    {
        $salon = $this->salonService->getOwnerSalon($owner);
        $recipientIds = $this->resolveRecipientIds($salon);
        $type = $this->normalizeType($data['type'] ?? NotificationTypes::GENERAL);
        $scheduledAt = $this->parseScheduledAt($data['scheduled_at'] ?? null);

        $broadcast = $this->notificationRepository->createBroadcast([
            'salon_id' => $salon->id,
            'owner_id' => $owner->id,
            'type' => $type,
            'title' => HtmlSanitizer::plainText($data['title']) ?? '',
            'content' => HtmlSanitizer::richHtml($data['content']) ?? '',
            'recipient_count' => count($recipientIds),
            'scheduled_at' => $scheduledAt,
            'sent_at' => null,
        ]);

        if ($scheduledAt->lte(now())) {
            DispatchNotificationBroadcastJob::dispatch($broadcast->id);

            return $broadcast->refresh();
        }

        return $broadcast->refresh();
    }

    public function getOwnerNotification(User $owner, string $id): OwnerNotificationBroadcast
    {
        $salon = $this->salonService->getOwnerSalon($owner);
        $broadcast = $this->notificationRepository->findBroadcastForOwner($id, $salon->id, $owner->id);

        if (! $broadcast) {
            throw new BusinessException('Không tìm thấy thông báo.', 404, 'NOTIFICATION_NOT_FOUND');
        }

        return $broadcast;
    }

    /**
     * @param  array{title?: string, content?: string, type?: string, scheduled_at?: string|null}  $data
     */
    public function updateOwnerNotification(User $owner, string $id, array $data): OwnerNotificationBroadcast
    {
        $broadcast = $this->getOwnerNotification($owner, $id);
        $payload = [];

        if (array_key_exists('title', $data)) {
            $payload['title'] = HtmlSanitizer::plainText($data['title']) ?? '';
        }

        if (array_key_exists('content', $data)) {
            $payload['content'] = HtmlSanitizer::richHtml($data['content']) ?? '';
        }

        if (array_key_exists('type', $data)) {
            $payload['type'] = $this->normalizeType($data['type']);
        }

        if (array_key_exists('scheduled_at', $data) && $broadcast->isPending()) {
            $payload['scheduled_at'] = $this->parseScheduledAt($data['scheduled_at']);
        }

        return DB::transaction(function () use ($broadcast, $payload) {
            $updated = $this->notificationRepository->updateBroadcast($broadcast, $payload);

            if ($updated->isPending()) {
                if ($updated->scheduled_at->lte(now())) {
                    DispatchNotificationBroadcastJob::dispatch($updated->id);

                    return $updated->refresh();
                }

                return $updated;
            }

            $syncPayload = array_intersect_key($payload, array_flip(['title', 'content', 'type']));
            if ($syncPayload !== []) {
                $this->notificationRepository->syncCustomerNotificationsForBroadcast(
                    $updated->id,
                    $syncPayload,
                );
            }

            return $updated;
        });
    }

    public function deleteOwnerNotification(User $owner, string $id): void
    {
        $broadcast = $this->getOwnerNotification($owner, $id);

        DB::transaction(function () use ($broadcast) {
            $this->notificationRepository->deleteCustomerNotificationsByBroadcast($broadcast->id);
            $this->notificationRepository->deleteBroadcast($broadcast);
        });
    }

    public function processDueScheduledNotifications(): int
    {
        $dueBroadcasts = $this->notificationRepository->getDueScheduledBroadcasts();
        $processed = 0;

        foreach ($dueBroadcasts as $broadcast) {
            DispatchNotificationBroadcastJob::dispatch($broadcast->id);
            $processed++;
        }

        return $processed;
    }

    public function dispatchBroadcastById(string $broadcastId): bool
    {
        $dispatched = false;

        DB::transaction(function () use ($broadcastId, &$dispatched) {
            $locked = OwnerNotificationBroadcast::query()
                ->whereKey($broadcastId)
                ->whereNull('sent_at')
                ->lockForUpdate()
                ->first();

            if (! $locked) {
                return;
            }

            $salon = Salon::query()->find($locked->salon_id);

            if (! $salon) {
                return;
            }

            $recipientIds = $this->resolveRecipientIds($salon);
            $this->dispatchBroadcast($locked, $recipientIds);
            $dispatched = true;
        });

        return $dispatched;
    }

    public function listOwnerHistory(User $owner, int $perPage = 20, ?string $search = null): LengthAwarePaginator
    {
        $salon = $this->salonService->getOwnerSalon($owner);

        return $this->notificationRepository->paginateOwnerHistory(
            $salon->id,
            $owner->id,
            $perPage,
            $search,
        );
    }

    public function listCustomerShopNotifications(User $customer, int $perPage = 20): LengthAwarePaginator
    {
        return $this->listShopNotifications($customer, $perPage);
    }

    public function listShopNotifications(?User $customer, int $perPage = 20): LengthAwarePaginator
    {
        $paginator = $this->notificationRepository->paginatePublicBroadcasts($perPage);

        if (! $customer) {
            return $paginator;
        }

        $broadcastIds = $paginator->getCollection()->pluck('id')->all();
        $readMap = $this->notificationRepository->getCustomerReadMapByBroadcastIds(
            $customer->id,
            $broadcastIds,
        );

        $paginator->getCollection()->transform(function (OwnerNotificationBroadcast $broadcast) use ($readMap) {
            if (array_key_exists($broadcast->id, $readMap)) {
                $broadcast->setAttribute(
                    'read_at',
                    $readMap[$broadcast->id] ? Carbon::parse($readMap[$broadcast->id]) : null,
                );
            }

            return $broadcast;
        });

        return $paginator;
    }

    public function listPublicShopBroadcasts(int $perPage = 20): LengthAwarePaginator
    {
        return $this->notificationRepository->paginatePublicBroadcasts($perPage);
    }

    public function countUnreadShopNotifications(User $customer): int
    {
        return $this->notificationRepository->countUnreadForUser($customer->id);
    }

    /**
     * @param  list<string>|null  $ids
     */
    public function markShopNotificationsAsRead(User $customer, ?array $ids = null): int
    {
        return $this->notificationRepository->markAsReadForUser($customer->id, $ids);
    }

    /**
     * @param  list<string>  $recipientIds
     */
    protected function dispatchBroadcast(OwnerNotificationBroadcast $broadcast, array $recipientIds): OwnerNotificationBroadcast
    {
        if ($broadcast->isSent()) {
            return $broadcast;
        }

        $sentAt = now();

        if ($recipientIds !== []) {
            $rows = array_map(fn (string $userId) => [
                'id' => (string) Str::uuid(),
                'user_id' => $userId,
                'salon_id' => $broadcast->salon_id,
                'broadcast_id' => $broadcast->id,
                'type' => $broadcast->type,
                'title' => $broadcast->title,
                'content' => $broadcast->content,
                'read_at' => null,
                'created_at' => $sentAt,
            ], $recipientIds);

            $this->notificationRepository->insertCustomerNotifications($rows);
        }

        return $this->notificationRepository->updateBroadcast($broadcast, [
            'recipient_count' => count($recipientIds),
            'sent_at' => $sentAt,
        ]);
    }

    /**
     * @return list<string>
     */
    protected function resolveRecipientIds(Salon $salon): array
    {
        $fromBookings = $this->notificationRepository->getDistinctCustomerIdsFromBookings($salon->id);

        $fromFavorites = $salon->favoritedByUsers()
            ->whereHas('role', fn ($query) => $query->where('name', 'customer'))
            ->pluck('users.id');

        return $fromBookings
            ->merge($fromFavorites)
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    protected function normalizeType(?string $type): string
    {
        $normalized = strtolower(trim((string) $type));

        if (! NotificationTypes::isValid($normalized)) {
            throw new BusinessException('Loại thông báo không hợp lệ.', 422, 'INVALID_NOTIFICATION_TYPE');
        }

        return $normalized;
    }

    protected function parseScheduledAt(?string $value): Carbon
    {
        if ($value === null || trim($value) === '') {
            throw new BusinessException('Vui lòng chọn thời gian gửi thông báo.', 422, 'SCHEDULED_AT_REQUIRED');
        }

        try {
            $timezone = config('app.timezone', 'Asia/Ho_Chi_Minh');

            if (preg_match('/(?:Z|[+-]\d{2}:\d{2})$/i', $value)) {
                $scheduledAt = Carbon::parse($value)->timezone($timezone);
            } else {
                $scheduledAt = Carbon::parse($value, $timezone);
            }
        } catch (\Throwable) {
            throw new BusinessException('Thời gian gửi không hợp lệ.', 422, 'INVALID_SCHEDULED_AT');
        }

        if ($scheduledAt->lte(now())) {
            throw new BusinessException('Thời gian gửi phải lớn hơn thời điểm hiện tại.', 422, 'SCHEDULED_AT_MUST_BE_FUTURE');
        }

        return $scheduledAt;
    }
}
