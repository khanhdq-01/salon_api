<?php

namespace App\Services\Staff;

use App\Contracts\Services\Owner\OwnerSalonSettingsServiceInterface;
use App\Contracts\Services\Staff\StaffPortalServiceInterface;
use App\Exceptions\BusinessException;
use App\Models\Booking;
use App\Models\Staff;
use App\Models\StaffSchedule;
use App\Models\User;
use App\Repositories\Interfaces\Owner\BookingRepositoryInterface;
use App\Repositories\Interfaces\Owner\StaffRepositoryInterface;
use App\Support\AvailableSlotsCache;
use App\Support\TimeFormat;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class StaffPortalService implements StaffPortalServiceInterface
{
    public function __construct(
        protected StaffRepositoryInterface $staffRepository,
        protected BookingRepositoryInterface $bookingRepository,
        protected OwnerSalonSettingsServiceInterface $salonSettingsService,
    ) {}

    public function getProfile(User $staffUser): array
    {
        $staff = $this->resolveStaffProfile($staffUser, ['salon:id,name', 'services:id,name', 'user:id,name,email,phone']);

        return $this->formatStaffContext($staffUser, $staff);
    }

    public function updateProfile(User $staffUser, array $data): User
    {
        $staff = $this->resolveStaffProfile($staffUser);

        $staffUser->fill([
            'name' => $data['name'] ?? $staffUser->name,
            'phone' => array_key_exists('phone', $data) ? $data['phone'] : $staffUser->phone,
        ]);
        $staffUser->save();

        if (isset($data['name']) && $data['name']) {
            $staff->update(['name' => $data['name']]);
        }

        return $staffUser->fresh(['role', 'staffProfile.salon:id,name']);
    }

    public function getDashboard(User $staffUser, array $filters = []): array
    {
        $staff = $this->resolveStaffProfile($staffUser);
        [$start, $end, $scheduleStart, $scheduleEnd, $period, $referenceDate] = $this->resolveDashboardDateRange($filters);

        $baseQuery = Booking::query()
            ->where('staff_id', $staff->id);

        if ($start && $end) {
            $baseQuery->whereBetween('booking_date', [$start->toDateString(), $end->toDateString()]);
        }

        $totalBookings = (clone $baseQuery)
            ->where('status', '!=', Booking::STATUS_CANCELLED)
            ->count();

        $completedBookings = (clone $baseQuery)
            ->where('status', Booking::STATUS_COMPLETED)
            ->count();

        $totalRevenue = (int) (clone $baseQuery)
            ->where('status', Booking::STATUS_COMPLETED)
            ->sum('total_price');

        $pendingSchedulesQuery = StaffSchedule::query()
            ->where('staff_id', $staff->id)
            ->where('status', StaffSchedule::STATUS_PENDING);

        if ($scheduleStart && $scheduleEnd) {
            $pendingSchedulesQuery->whereBetween('work_date', [
                $scheduleStart->toDateString(),
                $scheduleEnd->toDateString(),
            ]);
        }

        $pendingSchedules = $pendingSchedulesQuery->count();

        return [
            'staff_id' => $staff->id,
            'staff_name' => $staff->name,
            'salon_name' => $staff->salon?->name,
            'period' => $period,
            'date' => $referenceDate?->toDateString(),
            'range' => [
                'from' => $start?->toDateString(),
                'to' => $end?->toDateString(),
            ],
            'schedule_range' => [
                'from' => $scheduleStart?->toDateString(),
                'to' => $scheduleEnd?->toDateString(),
            ],
            'pending_schedules' => $pendingSchedules,
            'total_bookings' => $totalBookings,
            'completed_bookings' => $completedBookings,
            'total_revenue' => $totalRevenue,
        ];
    }

    public function listSchedules(User $staffUser): array
    {
        $staff = $this->resolveStaffProfile($staffUser, ['schedules']);

        return $staff->schedules
            ->sortBy('work_date')
            ->map(fn (StaffSchedule $schedule) => [
                'id' => $schedule->id,
                'work_date' => $this->formatWorkDate($schedule->work_date),
                'start_time' => substr((string) $schedule->start_time, 0, 5),
                'end_time' => substr((string) $schedule->end_time, 0, 5),
                'status' => $schedule->status,
                'submitted_by' => $schedule->submitted_by,
                'note' => $schedule->note,
                'approved_at' => TimeFormat::toIso8601($schedule->approved_at),
            ])
            ->values()
            ->all();
    }

    public function paginateWorkSchedules(User $staffUser, array $filters): LengthAwarePaginator
    {
        $staff = $this->resolveStaffProfile($staffUser);

        $query = StaffSchedule::query()
            ->where('staff_id', $staff->id)
            ->orderByDesc('work_date')
            ->orderByDesc('start_time');

        [$start, $end] = $this->resolveListDateRange($filters);
        $query->whereDate('work_date', '>=', $start->toDateString());
        $query->whereDate('work_date', '<=', $end->toDateString());

        $perPage = (int) ($filters['per_page'] ?? 10);
        $page = (int) ($filters['page'] ?? 1);

        return $query->paginate($perPage, ['*'], 'page', $page);
    }

    public function submitSchedules(User $staffUser, array $schedules): array
    {
        $staff = $this->resolveStaffProfile($staffUser);
        $salonSettings = $this->salonSettingsService->getForSalon($staff->salon_id);
        $autoApprove = $salonSettings->auto_approve_work_schedule;

        $normalized = collect($schedules)->map(function (array $item) {
            $workDate = $item['work_date'] ?? $item['date'] ?? null;
            $start = $item['start_time'] ?? $item['start'] ?? null;
            $end = $item['end_time'] ?? $item['end'] ?? null;

            if (! $workDate || ! $start || ! $end) {
                throw new BusinessException('Lịch làm việc không hợp lệ.', 'INVALID_SCHEDULE');
            }

            return [
                'workDate' => $workDate,
                'start' => \App\Support\TimeFormat::normalize($start),
                'end' => \App\Support\TimeFormat::normalize($end),
            ];
        });

        DB::transaction(function () use ($staff, $normalized, $autoApprove) {
            StaffSchedule::query()
                ->where('staff_id', $staff->id)
                ->where('status', StaffSchedule::STATUS_PENDING)
                ->where('submitted_by', StaffSchedule::SUBMITTED_BY_STAFF)
                ->delete();

            foreach ($normalized as $item) {
                $hasApproved = StaffSchedule::query()
                    ->where('staff_id', $staff->id)
                    ->whereDate('work_date', $item['workDate'])
                    ->where('status', StaffSchedule::STATUS_APPROVED)
                    ->exists();

                if ($hasApproved) {
                    throw new BusinessException(
                        'Ngày '.$item['workDate'].' đã có lịch được duyệt. Vui lòng liên hệ chủ salon để thay đổi.',
                        'SCHEDULE_LOCKED'
                    );
                }

                $scheduleData = [
                    'start_time' => $item['start'],
                    'end_time' => $item['end'],
                    'status' => $autoApprove ? StaffSchedule::STATUS_APPROVED : StaffSchedule::STATUS_PENDING,
                    'submitted_by' => StaffSchedule::SUBMITTED_BY_STAFF,
                    'approved_by' => null,
                    'approved_at' => $autoApprove ? now() : null,
                ];

                StaffSchedule::query()->updateOrCreate(
                    [
                        'staff_id' => $staff->id,
                        'work_date' => $item['workDate'],
                    ],
                    $scheduleData
                );
            }
        });

        if ($autoApprove) {
            AvailableSlotsCache::forgetSalonDates(
                $staff->salon_id,
                $normalized->pluck('workDate')->all()
            );
        }

        return $this->listSchedules($staffUser);
    }

    public function getReport(User $staffUser, array $filters): array
    {
        $staff = $this->resolveStaffProfile($staffUser);
        $period = $filters['period'] ?? $filters['summary_period'] ?? 'month';
        $referenceDate = $this->parseReferenceDate($filters['date'] ?? $filters['summary_date'] ?? null);
        [$start, $end] = $this->resolveDateRange($period, $referenceDate);

        $baseQuery = Booking::query()
            ->where('staff_id', $staff->id)
            ->whereBetween('booking_date', [$start->toDateString(), $end->toDateString()]);

        $completed = (clone $baseQuery)->where('status', Booking::STATUS_COMPLETED);
        $totalBookings = (clone $baseQuery)->where('status', '!=', Booking::STATUS_CANCELLED)->count();
        $totalRevenue = (int) (clone $completed)->sum('total_price');

        $chart = $this->buildRevenueChart($staff->id, $period, $referenceDate);

        return [
            'period' => $period,
            'date' => $referenceDate->toDateString(),
            'range' => [
                'from' => $start->toDateString(),
                'to' => $end->toDateString(),
            ],
            'summary' => [
                'total_revenue' => $totalRevenue,
                'total_bookings' => $totalBookings,
                'completed_bookings' => (clone $completed)->count(),
            ],
            'chart' => $chart,
        ];
    }

    protected function resolveStaffProfile(User $staffUser, array $relations = ['salon:id,name,owner_id']): Staff
    {
        if (! $staffUser->isStaff()) {
            throw new BusinessException('Tài khoản không phải nhân viên.', 'FORBIDDEN', 403);
        }

        $staff = $staffUser->staffProfile()->with($relations)->first();

        if (! $staff) {
            throw new BusinessException('Không tìm thấy hồ sơ nhân viên liên kết.', 'STAFF_PROFILE_NOT_FOUND', 404);
        }

        return $staff;
    }

    protected function formatStaffContext(User $staffUser, Staff $staff): array
    {
        return [
            'user' => [
                'id' => $staffUser->id,
                'name' => $staffUser->name,
                'email' => $staffUser->email,
                'phone' => $staffUser->phone,
                'role' => $staffUser->role?->name,
                'owner_id' => $staffUser->owner_id,
            ],
            'staff' => [
                'id' => $staff->id,
                'salon_id' => $staff->salon_id,
                'salon_name' => $staff->salon?->name,
                'name' => $staff->name,
                'is_active' => $staff->is_active,
                'services' => $staff->relationLoaded('services')
                    ? $staff->services->map(fn ($service) => ['id' => $service->id, 'name' => $service->name])->values()->all()
                    : [],
            ],
        ];
    }

    protected function resolveDashboardDateRange(array $filters): array
    {
        if (! empty($filters['start_date']) && ! empty($filters['end_date'])) {
            $start = Carbon::parse($filters['start_date'])->startOfDay();
            $end = Carbon::parse($filters['end_date'])->endOfDay();

            return [$start, $end, $start, $end, 'custom', $start];
        }

        if (! empty($filters['period']) || ! empty($filters['date'])) {
            $period = $filters['period'] ?? 'day';
            $referenceDate = $this->parseReferenceDate($filters['date'] ?? null);
            [$start, $end] = $this->resolveDateRange($period, $referenceDate);
            [$scheduleStart, $scheduleEnd] = $this->resolveDateRange($period, $referenceDate, capEndToToday: false);

            return [$start, $end, $scheduleStart, $scheduleEnd, $period, $referenceDate];
        }

        $today = Carbon::today();

        return [
            $today->copy()->startOfDay(),
            $today->copy()->endOfDay(),
            $today->copy()->startOfDay(),
            $today->copy()->endOfDay(),
            'day',
            $today,
        ];
    }

    protected function resolveListDateRange(array $filters): array
    {
        if (! empty($filters['start_date']) && ! empty($filters['end_date'])) {
            $start = Carbon::parse($filters['start_date'])->startOfDay();
            $end = Carbon::parse($filters['end_date'])->endOfDay();

            return [$start, $end];
        }

        $today = Carbon::today();

        return [$today->copy()->startOfDay(), $today->copy()->endOfDay()];
    }

    protected function parseReferenceDate(?string $value): Carbon
    {
        $today = Carbon::today();
        if (! $value) {
            return $today;
        }

        $date = Carbon::parse($value)->startOfDay();

        return $date->gt($today) ? $today : $date;
    }

    protected function resolveDateRange(string $rangeType, Carbon $referenceDate, bool $capEndToToday = true): array
    {
        $today = Carbon::today();

        $start = match ($rangeType) {
            'day' => $referenceDate->copy()->startOfDay(),
            'week' => $referenceDate->copy()->startOfWeek(Carbon::MONDAY)->startOfDay(),
            'month' => $referenceDate->copy()->startOfMonth()->startOfDay(),
            'year' => $referenceDate->copy()->startOfYear()->startOfDay(),
            default => $referenceDate->copy()->startOfMonth()->startOfDay(),
        };

        $end = match ($rangeType) {
            'day' => $referenceDate->copy()->endOfDay(),
            'week' => $referenceDate->copy()->endOfWeek(Carbon::SUNDAY)->endOfDay(),
            'month' => $referenceDate->copy()->endOfMonth()->endOfDay(),
            'year' => $referenceDate->copy()->endOfYear()->endOfDay(),
            default => $referenceDate->copy()->endOfMonth()->endOfDay(),
        };

        if ($capEndToToday && $end->gt($today->endOfDay())) {
            $end = $today->copy()->endOfDay();
        }

        if ($start->gt($end)) {
            $start = $end->copy()->startOfDay();
        }

        return [$start, $end];
    }

    protected function buildRevenueChart(string $staffId, string $period, Carbon $referenceDate): array
    {
        $labels = [];
        $revenue = [];

        if ($period === 'day') {
            $start = $referenceDate->copy()->subDays(6);
            for ($i = 0; $i < 7; $i++) {
                $day = $start->copy()->addDays($i);
                if ($day->gt(Carbon::today())) {
                    break;
                }
                $labels[] = $day->format('d/m');
                $revenue[] = $this->sumStaffRevenue($staffId, $day, $day);
            }

            return compact('labels', 'revenue');
        }

        if ($period === 'week') {
            $cursor = $referenceDate->copy()->endOfWeek(Carbon::SUNDAY);
            for ($i = 6; $i >= 0; $i--) {
                $weekEnd = $cursor->copy()->subWeeks($i);
                $weekStart = $weekEnd->copy()->startOfWeek(Carbon::MONDAY);
                if ($weekStart->gt(Carbon::today())) {
                    continue;
                }
                if ($weekEnd->gt(Carbon::today())) {
                    $weekEnd = Carbon::today();
                }
                $labels[] = 'Tuần '.$weekStart->format('d/m');
                $revenue[] = $this->sumStaffRevenue($staffId, $weekStart, $weekEnd);
            }

            return compact('labels', 'revenue');
        }

        if ($period === 'year') {
            $yearStart = $referenceDate->copy()->startOfYear();
            for ($month = 1; $month <= 12; $month++) {
                $monthStart = $yearStart->copy()->month($month)->startOfMonth();
                $monthEnd = $monthStart->copy()->endOfMonth();
                if ($monthStart->gt(Carbon::today())) {
                    break;
                }
                if ($monthEnd->gt(Carbon::today())) {
                    $monthEnd = Carbon::today();
                }
                $labels[] = 'T'.$month;
                $revenue[] = $this->sumStaffRevenue($staffId, $monthStart, $monthEnd);
            }

            return compact('labels', 'revenue');
        }

        $cursor = $referenceDate->copy()->startOfMonth();
        for ($i = 5; $i >= 0; $i--) {
            $month = $cursor->copy()->subMonths($i);
            $monthEnd = $month->copy()->endOfMonth();
            if ($month->gt(Carbon::today()->startOfMonth())) {
                continue;
            }
            if ($monthEnd->gt(Carbon::today())) {
                $monthEnd = Carbon::today();
            }
            $labels[] = 'T'.$month->month.'/'.$month->format('y');
            $revenue[] = $this->sumStaffRevenue($staffId, $month, $monthEnd);
        }

        return compact('labels', 'revenue');
    }

    public function getCalendarDay(User $staffUser, string $date): array
    {
        $staff = $this->resolveStaffProfile($staffUser, ['salon:id,name,open_time,close_time', 'schedules']);

        $schedule = $staff->schedules
            ->filter(fn (StaffSchedule $row) => $this->formatWorkDate($row->work_date) === $date)
            ->sortByDesc(fn (StaffSchedule $row) => $row->status === StaffSchedule::STATUS_APPROVED ? 1 : 0)
            ->first();

        $bookings = Booking::query()
            ->with(['customer:id,name,phone', 'bookingServices.service:id,name', 'seat:id,name'])
            ->where('staff_id', $staff->id)
            ->whereDate('booking_date', $date)
            ->where('status', '!=', Booking::STATUS_CANCELLED)
            ->orderBy('booking_time')
            ->get()
            ->map(fn (Booking $booking) => $this->formatCalendarBooking($booking))
            ->values()
            ->all();

        $settings = $this->salonSettingsService->getForSalon($staff->salon_id);

        return [
            'date' => $date,
            'staff' => [
                'id' => $staff->id,
                'name' => $staff->name,
            ],
            'salon' => [
                'name' => $staff->salon?->name,
                'open_time' => substr((string) ($staff->salon?->open_time ?? '09:00'), 0, 5),
                'close_time' => substr((string) ($staff->salon?->close_time ?? '20:00'), 0, 5),
                'booking_interval_minutes' => (int) $settings->booking_interval_minutes,
            ],
            'schedule' => $schedule ? [
                'work_date' => $this->formatWorkDate($schedule->work_date),
                'start_time' => substr((string) $schedule->start_time, 0, 5),
                'end_time' => substr((string) $schedule->end_time, 0, 5),
                'status' => $schedule->status,
                'submitted_by' => $schedule->submitted_by,
            ] : null,
            'bookings' => $bookings,
        ];
    }

    public function paginateCalendar(User $staffUser, array $filters): LengthAwarePaginator
    {
        $staff = $this->resolveStaffProfile($staffUser);

        $query = Booking::query()
            ->with(['customer:id,name,phone', 'bookingServices.service:id,name', 'seat:id,name'])
            ->where('staff_id', $staff->id)
            ->where('status', '!=', Booking::STATUS_CANCELLED)
            ->orderByDesc('booking_date')
            ->orderBy('booking_time');

        [$start, $end] = $this->resolveListDateRange($filters);
        $query->whereDate('booking_date', '>=', $start->toDateString());
        $query->whereDate('booking_date', '<=', $end->toDateString());

        $perPage = (int) ($filters['per_page'] ?? 10);
        $page = (int) ($filters['page'] ?? 1);

        return $query->paginate($perPage, ['*'], 'page', $page);
    }

    public function completeAssignedBooking(User $staffUser, string $bookingId): array
    {
        $staff = $this->resolveStaffProfile($staffUser);

        $booking = Booking::query()
            ->with(['customer:id,name,phone', 'bookingServices.service:id,name', 'seat:id,name'])
            ->whereKey($bookingId)
            ->where('staff_id', $staff->id)
            ->first();

        if (! $booking) {
            throw new BusinessException(
                'Không tìm thấy lịch hẹn được phân công cho bạn.',
                'BOOKING_NOT_FOUND',
                404
            );
        }

        if ($booking->status !== Booking::STATUS_CONFIRMED) {
            throw new BusinessException(
                'Chỉ có thể hoàn thành lịch đã xác nhận.',
                'INVALID_BOOKING_STATUS'
            );
        }

        $booking->update(['status' => Booking::STATUS_COMPLETED]);

        return $this->formatCalendarBooking(
            $booking->fresh(['customer:id,name,phone', 'bookingServices.service:id,name', 'seat:id,name'])
        );
    }

    protected function formatCalendarBooking(Booking $booking): array
    {
        $startTime = substr((string) $booking->booking_time, 0, 5);

        return [
            'id' => $booking->id,
            'customer' => $booking->customer?->name ?? $booking->walk_in_customer_name,
            'customer_phone' => $booking->customer?->phone,
            'service' => $booking->bookingServices
                ->map(fn ($line) => $line->service?->name)
                ->filter()
                ->implode(', ') ?: null,
            'date' => $this->formatWorkDate($booking->booking_date),
            'start_time' => $startTime,
            'end_time' => $this->addMinutesToTime($startTime, (int) $booking->total_duration_minutes),
            'status' => $booking->status,
            'total_price' => (int) $booking->total_price,
            'seat' => $booking->seat?->name,
        ];
    }

    protected function sumStaffRevenue(string $staffId, Carbon $start, Carbon $end): int
    {
        return (int) Booking::query()
            ->where('staff_id', $staffId)
            ->where('status', Booking::STATUS_COMPLETED)
            ->whereBetween('booking_date', [$start->toDateString(), $end->toDateString()])
            ->sum('total_price');
    }

    protected function formatWorkDate(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof \DateTimeInterface) {
            return $value->format('Y-m-d');
        }

        return substr((string) $value, 0, 10);
    }

    protected function addMinutesToTime(string $time, int $minutes): string
    {
        [$hour, $minute] = array_map('intval', explode(':', substr($time, 0, 5)));
        $totalMinutes = ($hour * 60) + $minute + $minutes;

        return sprintf('%02d:%02d', intdiv($totalMinutes, 60), $totalMinutes % 60);
    }
}
