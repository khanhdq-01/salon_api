<?php

namespace App\Services\Admin;

use App\Contracts\Services\Admin\AdminBookingManagementServiceInterface;
use App\Contracts\Services\Customer\BookingServiceInterface;
use App\Models\Booking;
use App\Models\User;
use App\Support\BookingMapper;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class AdminBookingManagementService implements AdminBookingManagementServiceInterface
{
    public function __construct(
        protected BookingServiceInterface $bookingService
    ) {}

    public function listBookings(array $filters): LengthAwarePaginator
    {
        return $this->bookingService->listBookings(
            BookingMapper::normalizeListFilters($filters),
            $this->adminUser()
        );
    }

    public function getBooking(string $id): Booking
    {
        return $this->bookingService->getBookingById($id, $this->adminUser());
    }

    public function updateStatus(string $id, string $status, ?string $reason = null): Booking
    {
        return $this->bookingService->updateBookingStatus($id, $status, $this->adminUser());
    }

    public function confirmBooking(string $id): Booking
    {
        return $this->bookingService->confirmBooking($id, $this->adminUser());
    }

    public function completeBooking(string $id): Booking
    {
        return $this->bookingService->completeBooking($id, $this->adminUser());
    }

    public function cancelBooking(string $id, ?string $reason = null): Booking
    {
        return $this->bookingService->cancelBooking($id, [
            'reason' => $reason ?? 'Admin cancelled',
        ], $this->adminUser());
    }

    public function deleteBooking(string $id): bool
    {
        return $this->bookingService->deleteBooking($id, $this->adminUser());
    }

    protected function adminUser(): User
    {
        $user = Auth::user();

        if (! $user instanceof User || ! $user->isAdmin()) {
            throw new \App\Exceptions\BusinessException('Forbidden.', 'FORBIDDEN', 403);
        }

        return $user;
    }
}
