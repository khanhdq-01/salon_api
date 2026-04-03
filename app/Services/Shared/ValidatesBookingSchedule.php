<?php

namespace App\Services\Shared;

use App\Exceptions\BusinessException;
use App\Models\Booking;
use App\Models\Salon;
use Illuminate\Support\Collection;

trait ValidatesBookingSchedule
{
    protected function assertSalonBookable(Salon $salon): void
    {
        if (! $salon->isPubliclyVisible()) {
            throw new BusinessException('Salon không khả dụng để đặt lịch.', 'SALON_NOT_AVAILABLE', 404);
        }
    }

    protected function assertWithinOpeningHours(Salon $salon, string $bookingTime, int $durationMinutes): void
    {
        $open = $this->timeToMinutes(substr((string) $salon->open_time, 0, 5));
        $close = $this->timeToMinutes(substr((string) $salon->close_time, 0, 5));
        $start = $this->timeToMinutes(substr($bookingTime, 0, 5));
        $end = $start + $durationMinutes;

        if ($start < $open || $end > $close) {
            throw new BusinessException('Thời gian đặt lịch nằm ngoài giờ mở cửa salon.', 'OUTSIDE_OPENING_HOURS');
        }
    }

    protected function assertStaffWorksOnDate(string $staffId, string $date, string $bookingTime, int $durationMinutes): void
    {
        $schedule = $this->staffScheduleRepository->findApprovedForStaffOnDate($staffId, $date);

        if (! $schedule) {
            throw new BusinessException('Nhân viên không làm việc trong ngày này.', 'STAFF_NOT_SCHEDULED');
        }

        $start = $this->timeToMinutes(substr($bookingTime, 0, 5));
        $end = $start + $durationMinutes;
        $workStart = $this->timeToMinutes(substr((string) $schedule->start_time, 0, 5));
        $workEnd = $this->timeToMinutes(substr((string) $schedule->end_time, 0, 5));

        if ($start < $workStart || $end > $workEnd) {
            throw new BusinessException(
                'Thời gian đặt lịch nằm ngoài ca làm việc của nhân viên.',
                'OUTSIDE_STAFF_HOURS'
            );
        }
    }

    protected function assertBookingTimeAlignsWithInterval(string $bookingTime, int $intervalMinutes): void
    {
        $minutes = $this->timeToMinutes(substr($bookingTime, 0, 5));

        if ($intervalMinutes <= 0 || $minutes % $intervalMinutes !== 0) {
            throw new BusinessException(
                'Thời gian đặt lịch không khớp với khoảng cách lịch của salon.',
                'INVALID_BOOKING_INTERVAL'
            );
        }
    }

    protected function roundUpToInterval(int $minutes, int $intervalMinutes): int
    {
        if ($intervalMinutes <= 0) {
            return $minutes;
        }

        return (int) (ceil($minutes / $intervalMinutes) * $intervalMinutes);
    }

    /**
     * @return list<string> Times in H:i format
     */
    protected function generateTimeOptionsBetween(
        int $startMinutes,
        int $endMinutes,
        int $durationMinutes,
        int $intervalMinutes = 30
    ): array {
        if ($startMinutes + $durationMinutes > $endMinutes || $intervalMinutes <= 0) {
            return [];
        }

        $alignedStart = $this->roundUpToInterval($startMinutes, $intervalMinutes);
        $times = [];

        for ($minute = $alignedStart; $minute + $durationMinutes <= $endMinutes; $minute += $intervalMinutes) {
            $times[] = sprintf('%02d:%02d', intdiv($minute, 60), $minute % 60);
        }

        return $times;
    }

    protected function assertNoStaffScheduleConflict(
        Collection $existingBookings,
        string $bookingTime,
        int $durationMinutes,
        string $staffId
    ): void {
        if ($this->hasStaffScheduleConflict($existingBookings, $bookingTime, $durationMinutes, $staffId)) {
            throw new BusinessException('Nhân viên đã có lịch trong khung giờ này.', 'STAFF_SLOT_TAKEN', 409);
        }
    }

    protected function hasStaffScheduleConflict(
        Collection $existingBookings,
        string $bookingTime,
        int $durationMinutes,
        string $staffId
    ): bool {
        $start = $this->timeToMinutes(substr($bookingTime, 0, 5));
        $end = $start + $durationMinutes;

        foreach ($existingBookings as $booking) {
            if ($booking->staff_id !== $staffId) {
                continue;
            }

            $otherStart = $this->timeToMinutes(substr((string) $booking->booking_time, 0, 5));
            $otherEnd = $otherStart + max(1, (int) $booking->total_duration_minutes);

            if ($this->intervalsOverlap($start, $end, $otherStart, $otherEnd)) {
                return true;
            }
        }

        return false;
    }

    protected function assertNoScheduleConflict(
        Collection $existingBookings,
        string $bookingTime,
        int $durationMinutes,
        string $staffId,
        string $seatId
    ): void {
        $start = $this->timeToMinutes(substr($bookingTime, 0, 5));
        $end = $start + $durationMinutes;

        foreach ($existingBookings as $booking) {
            $otherStart = $this->timeToMinutes(substr((string) $booking->booking_time, 0, 5));
            $otherEnd = $otherStart + (int) $booking->total_duration_minutes;

            if (! $this->intervalsOverlap($start, $end, $otherStart, $otherEnd)) {
                continue;
            }

            if ($booking->staff_id === $staffId) {
                throw new BusinessException('Nhân viên đã có lịch trong khung giờ này.', 'STAFF_SLOT_TAKEN', 409);
            }

            if ($booking->seat_id === $seatId) {
                throw new BusinessException('Ghế đã được đặt trong khung giờ này.', 'SEAT_SLOT_TAKEN', 409);
            }
        }
    }

    protected function assertBookingStatus(Booking $booking, array $allowed): void
    {
        if (! in_array($booking->status, $allowed, true)) {
            throw new BusinessException(
                'Trạng thái booking không cho phép thao tác này.',
                'INVALID_BOOKING_STATUS'
            );
        }
    }

    protected function timeToMinutes(string $time): int
    {
        [$hour, $minute] = array_map('intval', explode(':', $time));

        return ($hour * 60) + $minute;
    }

    protected function intervalsOverlap(int $startA, int $endA, int $startB, int $endB): bool
    {
        return $startA < $endB && $startB < $endA;
    }

    protected function hasScheduleConflict(
        Collection $existingBookings,
        string $bookingTime,
        int $durationMinutes,
        string $staffId,
        string $seatId
    ): bool {
        $start = $this->timeToMinutes(substr($bookingTime, 0, 5));
        $end = $start + $durationMinutes;

        foreach ($existingBookings as $booking) {
            $otherStart = $this->timeToMinutes(substr((string) $booking->booking_time, 0, 5));
            $otherEnd = $otherStart + (int) $booking->total_duration_minutes;

            if (! $this->intervalsOverlap($start, $end, $otherStart, $otherEnd)) {
                continue;
            }

            if ($booking->staff_id === $staffId || $booking->seat_id === $seatId) {
                return true;
            }
        }

        return false;
    }
}
