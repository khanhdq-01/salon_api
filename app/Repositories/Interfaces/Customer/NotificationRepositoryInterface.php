<?php

namespace App\Repositories\Interfaces\Customer;

use App\Models\CustomerNotification;
use App\Models\OwnerNotificationBroadcast;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface NotificationRepositoryInterface
{
    public function createBroadcast(array $data): OwnerNotificationBroadcast;

    public function insertCustomerNotifications(array $rows): void;

    public function paginateOwnerHistory(
        string $salonId,
        string $ownerId,
        int $perPage,
        ?string $search = null,
    ): LengthAwarePaginator;

    public function findBroadcastForOwner(string $id, string $salonId, string $ownerId): ?OwnerNotificationBroadcast;

    public function updateBroadcast(OwnerNotificationBroadcast $broadcast, array $data): OwnerNotificationBroadcast;

    public function deleteBroadcast(OwnerNotificationBroadcast $broadcast): void;

    public function syncCustomerNotificationsForBroadcast(
        string $broadcastId,
        array $data,
    ): int;

    public function deleteCustomerNotificationsByBroadcast(string $broadcastId): int;

    /**
     * @param  list<string>  $broadcastIds
     * @return array<string, string|null>
     */
    public function getCustomerReadMapByBroadcastIds(string $userId, array $broadcastIds): array;

    /**
     * @return Collection<int, OwnerNotificationBroadcast>
     */
    public function getDueScheduledBroadcasts(int $limit = 50): Collection;

    public function paginateCustomerNotifications(string $userId, int $perPage): LengthAwarePaginator;

    public function paginatePublicBroadcasts(int $perPage): LengthAwarePaginator;

    public function getDistinctCustomerIdsFromBookings(string $salonId): Collection;

    public function countUnreadForUser(string $userId): int;

    /**
     * @param  list<string>|null  $ids
     */
    public function markAsReadForUser(string $userId, ?array $ids = null): int;
}
