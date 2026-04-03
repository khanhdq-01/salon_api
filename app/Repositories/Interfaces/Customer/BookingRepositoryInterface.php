<?php

namespace App\Repositories\Interfaces\Customer;

use App\Models\Booking;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface BookingRepositoryInterface
{
    public function findById(string $id, array $relations = []): ?Booking;

    public function paginate(array $filters): LengthAwarePaginator;

    public function create(array $data): Booking;

    public function update(Booking $booking, array $data): Booking;

    public function attachServices(Booking $booking, array $lines): void;

    public function getActiveBookingsForSlot(
        string $salonId,
        string $date,
        ?string $staffId = null,
        ?string $seatId = null,
        ?string $excludeBookingId = null
    ): Collection;

    public function incrementSalonBookingsCount(string $salonId): void;

    public function findReviewableForSalon(string $salonId, string $customerId, ?string $bookingId = null): ?Booking;
}
