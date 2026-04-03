<?php

namespace App\Services\Owner;

use App\Models\SalonSetting;
use App\Repositories\Interfaces\Owner\SalonSettingsRepositoryInterface;
use App\Models\Booking;
use App\Models\Salon;
use App\Models\Staff;
use App\Models\StaffSchedule;
use App\Repositories\Interfaces\Owner\BookingRepositoryInterface as OwnerBookingRepositoryInterface;
use App\Repositories\Interfaces\Owner\SalonRepositoryInterface;
use App\Repositories\Interfaces\Owner\SeatRepositoryInterface;
use App\Repositories\Interfaces\Owner\ServiceRepositoryInterface;
use App\Repositories\Interfaces\Owner\StaffRepositoryInterface;
use App\Repositories\Interfaces\Owner\StaffScheduleRepositoryInterface;
use App\Services\Shared\ValidatesBookingSchedule;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class SalonTodayAvailabilityService
{
    use ValidatesBookingSchedule;

    private const CACHE_TTL = 300;

    public function __construct(
        protected SalonRepositoryInterface $salonRepository,
        protected SeatRepositoryInterface $seatRepository,
        protected ServiceRepositoryInterface $serviceRepository,
        protected StaffRepositoryInterface $staffRepository,
        protected StaffScheduleRepositoryInterface $staffScheduleRepository,
        protected OwnerBookingRepositoryInterface $bookingRepository,
        protected SalonSettingsRepositoryInterface $salonSettingsRepository,
    ) {}

    /**
     * @return list<string>
     */
    public function getAvailableSalonIds(?string $date = null): array
    {
        $date = $date ?? now()->toDateString();
        $version = (int) Cache::get("salons:available_today:version:{$date}", 0);
        $cacheKey = sprintf(
            'salons:available_today:%s:v%d:%s',
            $date,
            $version,
            now()->format('Y-m-d-H-i')
        );

        return Cache::remember($cacheKey, self::CACHE_TTL, fn () => $this->computeAvailableSalonIds($date));
    }

    public function salonHasAvailabilityToday(Salon $salon, ?string $date = null): bool
    {
        return in_array($salon->id, $this->getAvailableSalonIds($date), true);
    }

    /**
     * @return list<string>
     */
    protected function computeAvailableSalonIds(string $date): array
    {
        $salons = $this->salonRepository->getPubliclyVisibleBasic();

        if ($salons->isEmpty()) {
            return [];
        }

        $salonIds = $salons->pluck('id')->all();

        $seatsBySalon = $this->seatRepository->getActiveBySalonIds($salonIds)
            ->groupBy('salon_id');

        $minDurationBySalon = $this->serviceRepository->getMinDurationBySalonIds($salonIds);

        $staffMembers = $this->staffRepository->getActiveBySalonIds(
            $salonIds,
            ['services' => fn ($query) => $query->active()->select('services.id', 'services.salon_id')]
        );

        $staffBySalon = $staffMembers->groupBy('salon_id');
        $staffIds = $staffMembers->pluck('id')->all();

        $schedulesByStaff = $this->staffScheduleRepository->getForStaffIdsOnDate(
            $staffIds,
            $date,
            StaffSchedule::STATUS_APPROVED
        )->keyBy('staff_id');

        $settingsBySalon = $this->salonSettingsRepository->getBySalonIds($salonIds);

        $bookingsBySalon = $this->bookingRepository->getActiveBookingsForSalonsOnDate($salonIds, $date)
            ->groupBy('salon_id');

        $availableIds = [];

        foreach ($salons as $salon) {
            $settings = $settingsBySalon->get($salon->id)
                ?? new SalonSetting(SalonSetting::defaultAttributes($salon->id));
            $intervalMinutes = $settings->booking_interval_minutes;

            if ($this->salonHasBookableSlot(
                $salon,
                $date,
                $seatsBySalon->get($salon->id, collect()),
                $staffBySalon->get($salon->id, collect()),
                $schedulesByStaff,
                $bookingsBySalon->get($salon->id, collect()),
                (int) ($minDurationBySalon[$salon->id] ?? 30),
                $intervalMinutes
            )) {
                $availableIds[] = $salon->id;
            }
        }

        return $availableIds;
    }

    protected function salonHasBookableSlot(
        Salon $salon,
        string $date,
        Collection $seats,
        Collection $staffMembers,
        Collection $schedulesByStaff,
        Collection $dayBookings,
        int $durationMinutes,
        int $intervalMinutes = SalonSetting::DEFAULT_BOOKING_INTERVAL_MINUTES
    ): bool {
        if ($seats->isEmpty() || $durationMinutes <= 0) {
            return false;
        }

        $eligibleStaff = $staffMembers
            ->filter(function (Staff $staff) use ($schedulesByStaff) {
                if (! $schedulesByStaff->has($staff->id)) {
                    return false;
                }

                return $staff->services->isNotEmpty();
            })
            ->values();

        if ($eligibleStaff->isEmpty()) {
            return false;
        }

        $salonOpen = $this->timeToMinutes(substr((string) $salon->open_time, 0, 5));
        $salonClose = $this->timeToMinutes(substr((string) $salon->close_time, 0, 5));
        $earliestStart = $this->resolveEarliestBookableMinutes($date, $salonOpen, $intervalMinutes);

        if ($earliestStart + $durationMinutes > $salonClose) {
            return false;
        }

        foreach ($this->generateTimeOptionsBetween($earliestStart, $salonClose, $durationMinutes, $intervalMinutes) as $time) {
            $normalizedTime = $time . ':00';

            foreach ($eligibleStaff as $staff) {
                $schedule = $schedulesByStaff->get($staff->id);
                $windowStart = max(
                    $earliestStart,
                    $this->timeToMinutes(substr((string) $schedule->start_time, 0, 5))
                );
                $windowEnd = min(
                    $salonClose,
                    $this->timeToMinutes(substr((string) $schedule->end_time, 0, 5))
                );
                $slotStart = $this->timeToMinutes($time);

                if ($slotStart < $windowStart || $slotStart + $durationMinutes > $windowEnd) {
                    continue;
                }

                foreach ($seats as $seat) {
                    if (! $this->hasScheduleConflict(
                        $dayBookings,
                        $normalizedTime,
                        $durationMinutes,
                        $staff->id,
                        $seat->id
                    )) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    protected function resolveEarliestBookableMinutes(string $date, int $salonOpenMinutes, int $intervalMinutes): int
    {
        if ($date !== now()->toDateString()) {
            return $salonOpenMinutes;
        }

        $currentMinutes = (now()->hour * 60) + now()->minute;
        $rounded = $this->roundUpToInterval($currentMinutes, $intervalMinutes);

        return max($salonOpenMinutes, $rounded);
    }
}
