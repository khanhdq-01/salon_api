<?php

namespace App\Repositories\Eloquent\Owner;

use App\Models\Booking;
use App\Repositories\Interfaces\Owner\BookingRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class BookingRepository implements BookingRepositoryInterface
{
    public function __construct(
        protected Booking $model
    ) {}

    public function countPendingBySalon(string $salonId): int
    {
        return $this->model->newQuery()
            ->where('salon_id', $salonId)
            ->where('status', Booking::STATUS_PENDING)
            ->count();
    }

    public function countTodayNonCancelledBySalon(string $salonId, Carbon $today): int
    {
        return $this->model->newQuery()
            ->where('salon_id', $salonId)
            ->whereDate('booking_date', $today)
            ->where('status', '!=', Booking::STATUS_CANCELLED)
            ->count();
    }

    public function sumTodayCompletedRevenueBySalon(string $salonId, Carbon $today): int
    {
        return (int) $this->model->newQuery()
            ->where('salon_id', $salonId)
            ->whereDate('booking_date', $today)
            ->where('status', Booking::STATUS_COMPLETED)
            ->sum('total_price');
    }

    public function sumMonthCompletedRevenueBySalon(string $salonId, Carbon $monthStart, Carbon $monthEnd): int
    {
        return (int) $this->model->newQuery()
            ->where('salon_id', $salonId)
            ->where('status', Booking::STATUS_COMPLETED)
            ->whereBetween('booking_date', [$monthStart->toDateString(), $monthEnd->toDateString()])
            ->sum('total_price');
    }

    public function buildUpcomingQuery(string $salonId): Builder
    {
        return $this->model->newQuery()
            ->where('salon_id', $salonId)
            ->whereIn('status', [Booking::STATUS_PENDING, Booking::STATUS_CONFIRMED])
            ->with([
                'customer:id,name,phone',
                'staff:id,name',
                'bookingServices.service:id,name',
            ])
            ->orderBy('booking_date')
            ->orderBy('booking_time');
    }

    public function countUpcomingBySalon(string $salonId): int
    {
        return (clone $this->buildUpcomingQuery($salonId))->count();
    }

    public function paginateUpcomingBySalon(string $salonId, int $perPage, int $page, ?Carbon $start = null, ?Carbon $end = null): LengthAwarePaginator
    {
        $query = clone $this->buildUpcomingQuery($salonId);

        if ($start && $end) {
            $query->whereBetween('booking_date', [$start->toDateString(), $end->toDateString()]);
        }

        return $query->paginate(
            perPage: $perPage,
            page: $page,
        );
    }

    public function countDistinctBusyStaffToday(string $salonId, Carbon $today): int
    {
        return $this->model->newQuery()
            ->where('salon_id', $salonId)
            ->whereDate('booking_date', $today)
            ->whereIn('status', [Booking::STATUS_PENDING, Booking::STATUS_CONFIRMED])
            ->whereNotNull('staff_id')
            ->distinct()
            ->count('staff_id');
    }

    public function countNonCancelledOnDateBySalon(string $salonId, Carbon $day): int
    {
        return $this->model->newQuery()
            ->where('salon_id', $salonId)
            ->whereDate('booking_date', $day)
            ->where('status', '!=', Booking::STATUS_CANCELLED)
            ->count();
    }

    public function sumCompletedRevenueBetweenDates(string $salonId, Carbon $start, Carbon $end): int
    {
        return (int) $this->model->newQuery()
            ->where('salon_id', $salonId)
            ->where('status', Booking::STATUS_COMPLETED)
            ->whereDate('booking_date', '>=', $start->toDateString())
            ->whereDate('booking_date', '<=', $end->toDateString())
            ->sum('total_price');
    }

    public function buildSummaryBaseQuery(string $salonId, Carbon $start, Carbon $end): Builder
    {
        return $this->model->newQuery()
            ->where('salon_id', $salonId)
            ->whereDate('booking_date', '>=', $start->toDateString())
            ->whereDate('booking_date', '<=', $end->toDateString());
    }

    public function countReturningCustomers(string $salonId, Carbon $start, Carbon $end): int
    {
        return (int) $this->model->newQuery()
            ->where('salon_id', $salonId)
            ->whereNotNull('customer_id')
            ->where('status', '!=', Booking::STATUS_CANCELLED)
            ->whereDate('booking_date', '>=', $start->toDateString())
            ->whereDate('booking_date', '<=', $end->toDateString())
            ->whereIn('customer_id', function ($query) use ($salonId) {
                $query->select('customer_id')
                    ->from('bookings')
                    ->where('salon_id', $salonId)
                    ->whereNotNull('customer_id')
                    ->where('status', '!=', Booking::STATUS_CANCELLED)
                    ->groupBy('customer_id')
                    ->havingRaw('COUNT(*) >= 2');
            })
            ->distinct()
            ->count('customer_id');
    }

    public function getTopServicesRows(string $salonId, Carbon $start, Carbon $end): Collection
    {
        return DB::table('booking_services')
            ->join('bookings', 'bookings.id', '=', 'booking_services.booking_id')
            ->leftJoin('services', 'services.id', '=', 'booking_services.service_id')
            ->leftJoin('service_style_options', 'service_style_options.id', '=', 'booking_services.service_style_option_id')
            ->where('bookings.salon_id', $salonId)
            ->where('bookings.status', '!=', Booking::STATUS_CANCELLED)
            ->whereDate('bookings.booking_date', '>=', $start->toDateString())
            ->whereDate('bookings.booking_date', '<=', $end->toDateString())
            ->groupBy('booking_services.service_id', 'booking_services.service_style_option_id')
            ->selectRaw('booking_services.service_id, booking_services.service_style_option_id, MAX(services.name) as service_name, MAX(service_style_options.name) as option_name, COUNT(*) as bookings_count')
            ->orderByDesc('bookings_count')
            ->limit(10)
            ->get();
    }

    public function getStaffBookingCounts(string $salonId, Carbon $start, Carbon $end): Collection
    {
        return DB::table('bookings')
            ->where('salon_id', $salonId)
            ->where('status', '!=', Booking::STATUS_CANCELLED)
            ->whereNotNull('staff_id')
            ->whereDate('booking_date', '>=', $start->toDateString())
            ->whereDate('booking_date', '<=', $end->toDateString())
            ->groupBy('staff_id')
            ->selectRaw('staff_id, COUNT(*) as bookings_count')
            ->pluck('bookings_count', 'staff_id');
    }

    public function countNonCancelledInPeriod(string $salonId, string $periodStart, string $periodEnd): int
    {
        return $this->model->newQuery()
            ->where('salon_id', $salonId)
            ->whereBetween('booking_date', [$periodStart, $periodEnd])
            ->where('status', '!=', Booking::STATUS_CANCELLED)
            ->count();
    }

    public function getActiveBookingsForSalonsOnDate(array $salonIds, string $date): Collection
    {
        return $this->model->newQuery()
            ->whereIn('salon_id', $salonIds)
            ->whereDate('booking_date', $date)
            ->whereIn('status', [Booking::STATUS_PENDING, Booking::STATUS_CONFIRMED])
            ->get(['salon_id', 'staff_id', 'seat_id', 'booking_time', 'total_duration_minutes']);
    }

    public function getDayBookingsForSalon(string $salonId, string $date): Collection
    {
        return $this->model->newQuery()
            ->where('salon_id', $salonId)
            ->whereDate('booking_date', $date)
            ->whereIn('status', [Booking::STATUS_PENDING, Booking::STATUS_CONFIRMED])
            ->get(['id', 'staff_id', 'booking_time', 'total_duration_minutes']);
    }
}
