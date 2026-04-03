<?php

namespace App\Contracts\Services\Admin;

interface AdminBookingManagementServiceInterface
{
    public function listBookings(array $filters): mixed;

    public function getBooking(string $id): mixed;

    public function updateStatus(string $id, string $status, ?string $reason = null): mixed;

    public function confirmBooking(string $id): mixed;

    public function completeBooking(string $id): mixed;

    public function cancelBooking(string $id, ?string $reason = null): mixed;

    public function deleteBooking(string $id): bool;
}
