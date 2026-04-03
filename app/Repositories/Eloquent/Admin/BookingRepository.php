<?php

namespace App\Repositories\Eloquent\Admin;

use App\Models\Booking;
use App\Repositories\Interfaces\Admin\BookingRepositoryInterface;
use Carbon\Carbon;

class BookingRepository implements BookingRepositoryInterface
{
    public function __construct(
        protected Booking $model
    ) {}

    public function countAll(): int
    {
        return $this->model->newQuery()->count();
    }

    public function sumCompletedRevenue(): int
    {
        return (int) $this->model->newQuery()
            ->where('status', Booking::STATUS_COMPLETED)
            ->sum('total_price');
    }

    public function countByDateExcludingCancelled(Carbon $day): int
    {
        return $this->model->newQuery()
            ->whereDate('booking_date', $day->toDateString())
            ->where('status', '!=', Booking::STATUS_CANCELLED)
            ->count();
    }

    public function countBetweenDatesExcludingCancelled(Carbon $start, Carbon $end): int
    {
        return $this->model->newQuery()
            ->whereBetween('booking_date', [$start->toDateString(), $end->toDateString()])
            ->where('status', '!=', Booking::STATUS_CANCELLED)
            ->count();
    }

    public function sumCompletedRevenueBetweenDates(Carbon $start, Carbon $end): int
    {
        return (int) $this->model->newQuery()
            ->whereBetween('booking_date', [$start->toDateString(), $end->toDateString()])
            ->where('status', Booking::STATUS_COMPLETED)
            ->sum('total_price');
    }

    public function sumRevenueByDate(Carbon $day): int
    {
        return (int) $this->model->newQuery()
            ->whereDate('booking_date', $day->toDateString())
            ->where('status', Booking::STATUS_COMPLETED)
            ->sum('total_price');
    }
}
