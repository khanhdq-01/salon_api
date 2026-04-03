<?php

namespace App\Contracts\Services\Customer;

interface BookingServiceInterface
{
    public function listBookings(array $filters, ?\App\Models\User $actor = null): mixed;

    public function createBooking(array $data, \App\Models\User $actor): mixed;

    public function getBookingById(string $id, ?\App\Models\User $actor = null): mixed;

    public function confirmBooking(string $id, \App\Models\User $actor): mixed;

    public function completeBooking(string $id, \App\Models\User $actor): mixed;

    public function cancelBooking(string $id, array $data, \App\Models\User $actor): mixed;

    public function rescheduleBooking(string $id, array $data, \App\Models\User $actor): mixed;

    public function updateBookingStatus(string $id, string $status, \App\Models\User $actor): mixed;

    public function deleteBooking(string $id, \App\Models\User $actor): bool;

    public function getAvailableSlots(string $salonId, array $filters): array;
}
