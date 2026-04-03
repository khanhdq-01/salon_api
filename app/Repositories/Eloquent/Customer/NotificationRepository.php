<?php

namespace App\Repositories\Eloquent\Customer;

use App\Models\Booking;
use App\Models\CustomerNotification;
use App\Models\OwnerNotificationBroadcast;
use App\Repositories\Interfaces\Customer\NotificationRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class NotificationRepository implements NotificationRepositoryInterface
{
    public function __construct(
        protected OwnerNotificationBroadcast $broadcastModel,
        protected CustomerNotification $notificationModel,
        protected Booking $bookingModel,
    ) {}

    public function createBroadcast(array $data): OwnerNotificationBroadcast
    {
        return $this->broadcastModel->newQuery()->create($data);
    }

    public function insertCustomerNotifications(array $rows): void
    {
        foreach (array_chunk($rows, 100) as $chunk) {
            $this->notificationModel->newQuery()->insert($chunk);
        }
    }

    public function paginateOwnerHistory(string $salonId, string $ownerId, int $perPage, ?string $search = null): LengthAwarePaginator
    {
        $query = $this->broadcastModel->newQuery()
            ->where('salon_id', $salonId)
            ->where('owner_id', $ownerId)
            ->orderByDesc('created_at');

        if ($search !== null && $search !== '') {
            $like = '%'.addcslashes($search, '%_\\').'%';
            $query->where(function ($builder) use ($like) {
                $builder
                    ->where('title', 'like', $like)
                    ->orWhere('content', 'like', $like)
                    ->orWhere('type', 'like', $like);
            });
        }

        return $query->paginate($perPage);
    }

    public function findBroadcastForOwner(string $id, string $salonId, string $ownerId): ?OwnerNotificationBroadcast
    {
        return $this->broadcastModel->newQuery()
            ->where('id', $id)
            ->where('salon_id', $salonId)
            ->where('owner_id', $ownerId)
            ->first();
    }

    public function updateBroadcast(OwnerNotificationBroadcast $broadcast, array $data): OwnerNotificationBroadcast
    {
        $broadcast->fill($data);
        $broadcast->save();

        return $broadcast->refresh();
    }

    public function deleteBroadcast(OwnerNotificationBroadcast $broadcast): void
    {
        $broadcast->delete();
    }

    public function syncCustomerNotificationsForBroadcast(string $broadcastId, array $data): int
    {
        return $this->notificationModel->newQuery()
            ->where('broadcast_id', $broadcastId)
            ->update($data);
    }

    public function deleteCustomerNotificationsByBroadcast(string $broadcastId): int
    {
        return $this->notificationModel->newQuery()
            ->where('broadcast_id', $broadcastId)
            ->delete();
    }

    public function getCustomerReadMapByBroadcastIds(string $userId, array $broadcastIds): array
    {
        if ($broadcastIds === []) {
            return [];
        }

        return $this->notificationModel->newQuery()
            ->where('user_id', $userId)
            ->whereIn('broadcast_id', $broadcastIds)
            ->get(['broadcast_id', 'read_at'])
            ->mapWithKeys(fn (CustomerNotification $notification) => [
                $notification->broadcast_id => $notification->read_at?->toIso8601String(),
            ])
            ->all();
    }

    public function getDueScheduledBroadcasts(int $limit = 50): Collection
    {
        return $this->broadcastModel->newQuery()
            ->whereNull('sent_at')
            ->where('scheduled_at', '<=', now())
            ->orderBy('scheduled_at')
            ->limit($limit)
            ->get();
    }

    public function paginateCustomerNotifications(string $userId, int $perPage): LengthAwarePaginator
    {
        return $this->notificationModel->newQuery()
            ->with('salon:id,name')
            ->where('user_id', $userId)
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }

    public function paginatePublicBroadcasts(int $perPage): LengthAwarePaginator
    {
        return $this->broadcastModel->newQuery()
            ->with([
                'salon' => function ($query) {
                    $query->select('id', 'name', 'image_url')
                        ->with(['images' => fn ($imageQuery) => $imageQuery->orderByDesc('created_at')->limit(1)]);
                },
            ])
            ->whereNotNull('sent_at')
            ->orderByDesc('sent_at')
            ->paginate($perPage);
    }

    public function getDistinctCustomerIdsFromBookings(string $salonId): Collection
    {
        return $this->bookingModel->newQuery()
            ->where('salon_id', $salonId)
            ->whereNotNull('customer_id')
            ->distinct()
            ->pluck('customer_id');
    }

    public function countUnreadForUser(string $userId): int
    {
        return $this->notificationModel->newQuery()
            ->where('user_id', $userId)
            ->whereNull('read_at')
            ->count();
    }

    public function markAsReadForUser(string $userId, ?array $ids = null): int
    {
        $query = $this->notificationModel->newQuery()
            ->where('user_id', $userId)
            ->whereNull('read_at');

        if ($ids !== null && $ids !== []) {
            $query->whereIn('id', $ids);
        }

        return $query->update(['read_at' => now()]);
    }
}
