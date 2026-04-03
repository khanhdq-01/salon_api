<?php

namespace App\Repositories\Eloquent\Customer;

use App\Repositories\Interfaces\Customer\BookingRepositoryInterface;
use App\Models\Booking;
use App\Models\BookingService as BookingServiceLine;
use App\Models\Salon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class BookingRepository implements BookingRepositoryInterface
{
    public function __construct(
        protected Booking $model
    ) {}

    public static function detailRelations(): array
    {
        return [
            'salon:id,name,address,owner_id',
            'customer:id,name,email,phone',
            'staff:id,name',
            'seat:id,name',
            'bookingServices' => fn ($query) => $query->orderBy('sort_order'),
            'bookingServices.service:id,name',
            'bookingServices.styleOption:id,name,extra_price,extra_duration',
        ];
    }

    public function findById(string $id, array $relations = []): ?Booking
    {
        return $this->model->newQuery()->with($relations ?: self::detailRelations())->find($id);
    }

    public function paginate(array $filters): LengthAwarePaginator
    {
        $query = $this->model->newQuery()->with(self::detailRelations());

        $this->applyFilters($query, $filters);
        $this->applySort($query, $filters);

        return $query->paginate(perPage: $filters['per_page'], page: $filters['page']);
    }

    protected function applySort(Builder $query, array $filters): void
    {
        if (($filters['sort'] ?? null) === 'created_at') {
            $query->orderByDesc('created_at');

            return;
        }

        $query
            ->orderByDesc('booking_date')
            ->orderByDesc('booking_time')
            ->orderByDesc('created_at');
    }

    public function create(array $data): Booking
    {
        return $this->model->newQuery()->create($data);
    }

    public function update(Booking $booking, array $data): Booking
    {
        $booking->update($data);

        return $booking->fresh(self::detailRelations());
    }

    public function attachServices(Booking $booking, array $lines): void
    {
        foreach ($lines as $line) {
            BookingServiceLine::query()->create([
                'booking_id' => $booking->id,
                'service_id' => $line['service_id'],
                'service_style_option_id' => $line['service_style_option_id'] ?? null,
                'price' => $line['price'],
                'duration_minutes' => $line['duration_minutes'],
                'sort_order' => $line['sort_order'],
            ]);
        }
    }

    public function getActiveBookingsForSlot(
        string $salonId,
        string $date,
        ?string $staffId = null,
        ?string $seatId = null,
        ?string $excludeBookingId = null
    ): Collection {
        $query = $this->model->newQuery()
            ->where('salon_id', $salonId)
            ->whereDate('booking_date', $date)
            ->whereIn('status', [Booking::STATUS_PENDING, Booking::STATUS_CONFIRMED]);

        if ($excludeBookingId) {
            $query->where('id', '!=', $excludeBookingId);
        }

        $query->where(function (Builder $q) use ($staffId, $seatId) {
            if ($staffId) {
                $q->orWhere('staff_id', $staffId);
            }
            if ($seatId) {
                $q->orWhere('seat_id', $seatId);
            }
        });

        return $query->get(['id', 'staff_id', 'seat_id', 'booking_time', 'total_duration_minutes']);
    }

    public function incrementSalonBookingsCount(string $salonId): void
    {
        Salon::query()->whereKey($salonId)->increment('bookings_count');
    }

    public function findReviewableForSalon(string $salonId, string $customerId, ?string $bookingId = null): ?Booking
    {
        $query = $this->model->newQuery()
            ->where('salon_id', $salonId)
            ->where('customer_id', $customerId)
            ->where('status', Booking::STATUS_COMPLETED)
            ->where('has_reviewed', false)
            ->whereDoesntHave('review');

        if ($bookingId) {
            $query->whereKey($bookingId);
        }

        return $query
            ->orderByDesc('booking_date')
            ->orderByDesc('booking_time')
            ->orderByDesc('created_at')
            ->first();
    }

    protected function applyFilters(Builder $query, array $filters): void
    {
        if (! empty($filters['salon_id'])) {
            $query->where('salon_id', $filters['salon_id']);
        }

        if (! empty($filters['customer_id'])) {
            $query->where('customer_id', $filters['customer_id']);
        }

        if (! empty($filters['staff_id'])) {
            $query->where('staff_id', $filters['staff_id']);
        }

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (! empty($filters['date'])) {
            $query->whereDate('booking_date', $filters['date']);
        }

        if (! empty($filters['date_from'])) {
            $query->whereDate('booking_date', '>=', $filters['date_from']);
        }

        if (! empty($filters['date_to'])) {
            $query->whereDate('booking_date', '<=', $filters['date_to']);
        }
    }
}
