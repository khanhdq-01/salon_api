<?php

namespace App\Services\Customer;

use App\Repositories\Interfaces\Customer\BookingRepositoryInterface;
use App\Repositories\Interfaces\Owner\BookingRepositoryInterface as OwnerBookingRepositoryInterface;
use App\Repositories\Interfaces\Owner\SalonRepositoryInterface;
use App\Repositories\Interfaces\Owner\SeatRepositoryInterface;
use App\Repositories\Interfaces\Owner\ServiceRepositoryInterface;
use App\Repositories\Interfaces\Owner\StaffRepositoryInterface;
use App\Repositories\Interfaces\Owner\StaffScheduleRepositoryInterface;
use App\Contracts\Services\Customer\BookingServiceInterface;
use App\Contracts\Services\Owner\OwnerPackageLimitServiceInterface;
use App\Contracts\Services\Owner\OwnerSalonSettingsServiceInterface;
use App\Exceptions\BusinessException;
use App\Models\Booking;
use App\Models\Salon;
use App\Models\Staff;
use App\Models\User;
use App\Repositories\Eloquent\Customer\BookingRepository;
use App\Support\AvailableSlotsCache;
use App\Support\BookingMapper;
use App\Support\BookingSlotLock;
use App\Support\ServiceStyleOptionResolver;
use App\Services\Shared\AssertsSalonOwnership;
use App\Services\Shared\ValidatesBookingSchedule;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class BookingService implements BookingServiceInterface
{
    use AssertsSalonOwnership;
    use ValidatesBookingSchedule;

    public function __construct(
        protected BookingRepositoryInterface $bookingRepository,
        protected SalonRepositoryInterface $salonRepository,
        protected ServiceRepositoryInterface $serviceRepository,
        protected StaffRepositoryInterface $staffRepository,
        protected SeatRepositoryInterface $seatRepository,
        protected StaffScheduleRepositoryInterface $staffScheduleRepository,
        protected OwnerBookingRepositoryInterface $ownerBookingRepository,
        protected OwnerPackageLimitServiceInterface $packageLimitService,
        protected OwnerSalonSettingsServiceInterface $salonSettingsService,
        protected BookingRequestEmailService $bookingRequestEmailService,
        protected BookingConfirmationEmailService $bookingConfirmationEmailService,
    ) {}

    public function listBookings(array $filters, ?User $actor = null): LengthAwarePaginator
    {
        $filters = BookingMapper::normalizeListFilters($filters);

        if ($actor?->isCustomer()) {
            $filters['customer_id'] = $actor->id;
            $filters['sort'] = $filters['sort'] ?? 'created_at';
        } elseif ($actor?->isOwner()) {
            $filters['salon_id'] = $filters['salon_id']
                ?? $this->resolveOwnerSalonId($this->salonRepository, $actor);

            $shouldDefaultToToday = empty($filters['status'])
                || $filters['status'] !== Booking::STATUS_PENDING;

            if (
                $shouldDefaultToToday
                && empty($filters['date_from'])
                && empty($filters['date_to'])
                && empty($filters['date'])
            ) {
                $today = now()->toDateString();
                $filters['date_from'] = $today;
                $filters['date_to'] = $today;
            }
        }

        return $this->bookingRepository->paginate($filters);
    }

    public function createBooking(array $data, User $actor): Booking
    {
        if (! $actor->isCustomer()) {
            throw new BusinessException('Chỉ khách hàng mới được tạo booking.', 'FORBIDDEN', 403);
        }

        $payload = BookingMapper::normalizeCreate($data);
        $salon = $this->findSalonOrFail($this->salonRepository, $payload['salon_id']);
        $this->assertSalonBookable($salon);
        $this->packageLimitService->assertCanAddBookingForSalon($salon->id);

        $services = $this->serviceRepository->findActiveByIdsForSalon($payload['service_ids'], $payload['salon_id']);

        if ($services->count() !== count(array_unique($payload['service_ids']))) {
            throw new BusinessException('Dịch vụ không hợp lệ hoặc không thuộc salon.', 'INVALID_SERVICES');
        }

        $staff = $this->staffRepository->findById($payload['staff_id'], ['services']);

        if (! $staff || $staff->salon_id !== $payload['salon_id'] || ! $staff->is_active) {
            throw new BusinessException('Nhân viên không hợp lệ.', 'INVALID_STAFF');
        }

        if (! $this->staffRepository->staffProvidesServices($staff, $services->pluck('id'))) {
            throw new BusinessException('Nhân viên không thực hiện được dịch vụ đã chọn.', 'STAFF_SERVICE_MISMATCH');
        }

        $seatId = $payload['seat_id'] ?? $this->resolveSalonSeats($payload['salon_id'])->first()?->id;

        if (! $seatId) {
            throw new BusinessException('Salon chưa có ghế để ghi nhận lịch.', 'INVALID_SEAT');
        }

        $payload['seat_id'] = $seatId;

        $resolved = ServiceStyleOptionResolver::resolveLines(
            $services,
            $payload['style_options'] ?? []
        );
        $totalPrice = $resolved['total_price'];
        $totalDuration = $resolved['total_duration'];
        $serviceLines = $resolved['lines'];

        $this->assertWithinOpeningHours($salon, $payload['booking_time'], $totalDuration);
        $salonSettings = $this->salonSettingsService->getForSalon($payload['salon_id']);
        $this->assertBookingTimeAlignsWithInterval(
            $payload['booking_time'],
            $salonSettings->booking_interval_minutes
        );
        $this->assertStaffWorksOnDate(
            $payload['staff_id'],
            $payload['booking_date'],
            $payload['booking_time'],
            $totalDuration
        );

        $initialStatus = Booking::STATUS_PENDING;

        $booking = BookingSlotLock::run(
            $payload['salon_id'],
            $payload['booking_date'],
            $payload['staff_id'],
            $payload['seat_id'],
            function () use ($payload, $actor, $services, $totalPrice, $totalDuration, $serviceLines, $salonSettings, &$initialStatus) {
                $conflicts = $this->bookingRepository->getActiveBookingsForSlot(
                    $payload['salon_id'],
                    $payload['booking_date'],
                    $payload['staff_id'],
                    $payload['seat_id']
                );

                $this->assertNoScheduleConflict(
                    $conflicts,
                    $payload['booking_time'],
                    $totalDuration,
                    $payload['staff_id'],
                    $payload['seat_id']
                );

                return DB::transaction(function () use ($payload, $actor, $services, $totalPrice, $totalDuration, $serviceLines, $salonSettings, &$initialStatus) {
                    $initialStatus = $salonSettings->auto_confirm_booking
                        ? Booking::STATUS_CONFIRMED
                        : Booking::STATUS_PENDING;

                    $booking = $this->bookingRepository->create([
                        'salon_id' => $payload['salon_id'],
                        'customer_id' => $actor->id,
                        'staff_id' => $payload['staff_id'],
                        'seat_id' => $payload['seat_id'],
                        'booking_date' => $payload['booking_date'],
                        'booking_time' => $payload['booking_time'],
                        'status' => $initialStatus,
                        'total_price' => $totalPrice,
                        'total_duration_minutes' => $totalDuration,
                        'customer_notes' => $payload['customer_notes'],
                        'created_by' => $actor->id,
                    ]);

                    $this->bookingRepository->attachServices($booking, $serviceLines);

                    foreach ($services as $service) {
                        $this->serviceRepository->incrementBookingsCount($service);
                    }

                    $this->bookingRepository->incrementSalonBookingsCount($payload['salon_id']);

                    return $this->bookingRepository->findById($booking->id, BookingRepository::detailRelations());
                });
            }
        );

        $this->invalidateAvailableSlotsForBooking($booking);

        if ($initialStatus === Booking::STATUS_CONFIRMED) {
            $this->bookingConfirmationEmailService->sendConfirmationEmail($booking);
        } else {
            $this->bookingRequestEmailService->sendBookingRequestEmail($booking);
        }

        return $booking;
    }

    public function getBookingById(string $id, ?User $actor = null): Booking
    {
        $booking = $this->findBookingOrFail($id);
        $this->assertCanViewBooking($booking, $actor);

        return $booking;
    }

    public function confirmBooking(string $id, User $actor): Booking
    {
        $booking = $this->findBookingOrFail($id);
        $this->assertCanManageBooking($booking, $actor);
        $this->assertBookingStatus($booking, [Booking::STATUS_PENDING]);

        $booking = $this->bookingRepository->update($booking, ['status' => Booking::STATUS_CONFIRMED]);

        $this->bookingConfirmationEmailService->sendConfirmationEmail($booking);

        return $booking;
    }

    public function completeBooking(string $id, User $actor): Booking
    {
        $booking = $this->findBookingOrFail($id);
        $this->assertCanManageBooking($booking, $actor);
        $this->assertBookingStatus($booking, [Booking::STATUS_CONFIRMED]);

        $booking = $this->bookingRepository->update($booking, ['status' => Booking::STATUS_COMPLETED]);
        $this->invalidateAvailableSlotsForBooking($booking);

        return $booking;
    }

    public function cancelBooking(string $id, array $data, User $actor): Booking
    {
        $booking = $this->findBookingOrFail($id);
        $cancel = BookingMapper::normalizeCancel($data);

        if ($actor->isCustomer()) {
            if ($booking->customer_id !== $actor->id) {
                throw new BusinessException('Không có quyền hủy booking này.', 'FORBIDDEN', 403);
            }
            $this->assertBookingStatus($booking, [Booking::STATUS_PENDING, Booking::STATUS_CONFIRMED]);

            $salonSettings = $this->salonSettingsService->getForSalon($booking->salon_id);
            if (! $salonSettings->customerCanCancelBooking($booking)) {
                throw new BusinessException(
                    'Đã quá thời hạn cho phép hủy lịch.',
                    'CANCELLATION_WINDOW_EXPIRED',
                    422
                );
            }
        } else {
            $this->assertCanManageBooking($booking, $actor);
            $this->assertBookingStatus($booking, [Booking::STATUS_PENDING, Booking::STATUS_CONFIRMED]);
        }

        $booking = $this->bookingRepository->update($booking, [
            'status' => Booking::STATUS_CANCELLED,
            'cancel_reason' => $cancel['cancel_reason'],
            'cancelled_by' => $actor->id,
        ]);

        $this->invalidateAvailableSlotsForBooking($booking);

        return $booking;
    }

    public function rescheduleBooking(string $id, array $data, User $actor): Booking
    {
        $booking = $this->findBookingOrFail($id, ['salon', 'services']);

        if ($actor->isCustomer()) {
            if ($booking->customer_id !== $actor->id) {
                throw new BusinessException('Không có quyền đổi lịch booking này.', 'FORBIDDEN', 403);
            }
        } elseif ($actor->isOwner()) {
            $this->assertCanManageBooking($booking, $actor);
        } elseif (! $actor->isAdmin()) {
            throw new BusinessException('Không có quyền đổi lịch booking này.', 'FORBIDDEN', 403);
        }

        $this->assertBookingStatus($booking, [Booking::STATUS_PENDING, Booking::STATUS_CONFIRMED]);

        $payload = BookingMapper::normalizeReschedule($data);
        $staffId = $payload['staff_id'] ?? $booking->staff_id;
        $seatId = $payload['seat_id'] ?? $booking->seat_id;

        $staff = $this->staffRepository->findById($staffId, ['services']);

        if (! $staff || $staff->salon_id !== $booking->salon_id || ! $staff->is_active) {
            throw new BusinessException('Nhân viên không hợp lệ.', 'INVALID_STAFF');
        }

        $serviceIds = $booking->services->pluck('id');

        if (! $this->staffRepository->staffProvidesServices($staff, $serviceIds)) {
            throw new BusinessException('Nhân viên không thực hiện được dịch vụ của booking.', 'STAFF_SERVICE_MISMATCH');
        }

        $seat = $this->seatRepository->findActiveByIdAndSalon($seatId, $booking->salon_id);

        if (! $seat) {
            throw new BusinessException('Ghế không hợp lệ.', 'INVALID_SEAT');
        }

        $duration = (int) $booking->total_duration_minutes;
        $salonSettings = $this->salonSettingsService->getForSalon($booking->salon_id);
        $this->assertWithinOpeningHours($booking->salon, $payload['booking_time'], $duration);
        $this->assertBookingTimeAlignsWithInterval(
            $payload['booking_time'],
            $salonSettings->booking_interval_minutes
        );
        $this->assertStaffWorksOnDate(
            $staffId,
            $payload['booking_date'],
            $payload['booking_time'],
            $duration
        );

        $previousDate = $this->bookingDateString($booking);

        $updatePayload = [
            'booking_date' => $payload['booking_date'],
            'booking_time' => $payload['booking_time'],
            'staff_id' => $staffId,
            'seat_id' => $seatId,
        ];

        $booking = BookingSlotLock::run(
            $booking->salon_id,
            $payload['booking_date'],
            $staffId,
            $seatId,
            function () use ($booking, $payload, $actor, $staffId, $seatId, $duration, $salonSettings, &$updatePayload) {
                $conflicts = $this->bookingRepository->getActiveBookingsForSlot(
                    $booking->salon_id,
                    $payload['booking_date'],
                    $staffId,
                    $seatId,
                    $booking->id
                );

                $this->assertNoScheduleConflict($conflicts, $payload['booking_time'], $duration, $staffId, $seatId);

                if ($actor->isCustomer()) {
                    $updatePayload['status'] = $salonSettings->auto_confirm_booking
                        ? Booking::STATUS_CONFIRMED
                        : Booking::STATUS_PENDING;
                }

                return $this->bookingRepository->update($booking, $updatePayload);
            }
        );

        $this->invalidateAvailableSlotsForBooking($booking, $previousDate);

        return $booking;
    }

    public function deleteBooking(string $id, User $actor): bool
    {
        $booking = $this->findBookingOrFail($id);
        $this->assertCanManageBooking($booking, $actor);

        if (AvailableSlotsCache::statusAffectsSlots($booking->status)) {
            $this->invalidateAvailableSlotsForBooking($booking);
        }

        return (bool) $booking->delete();
    }

    public function updateBookingStatus(string $id, string $status, User $actor): Booking
    {
        $booking = $this->findBookingOrFail($id);
        $this->assertCanManageBooking($booking, $actor);

        if (! in_array($status, [
            Booking::STATUS_PENDING,
            Booking::STATUS_CONFIRMED,
            Booking::STATUS_COMPLETED,
            Booking::STATUS_CANCELLED,
            Booking::STATUS_NO_SHOW,
        ], true)) {
            throw new BusinessException('Status không hợp lệ.', 'INVALID_STATUS');
        }

        $previousStatus = $booking->status;
        $booking = $this->bookingRepository->update($booking, ['status' => $status]);

        if (AvailableSlotsCache::shouldInvalidateOnStatusChange($previousStatus, $status)) {
            $this->invalidateAvailableSlotsForBooking($booking);
        }

        return $booking;
    }

    public function getAvailableSlots(string $salonId, array $filters): array
    {
        $filters = BookingMapper::normalizeAvailableSlotFilters($filters);
        $date = $filters['date'] ?? null;
        $serviceIds = $filters['service_ids'] ?? [];

        if (! $date || empty($serviceIds)) {
            throw new BusinessException('date và service_ids là bắt buộc.', 'VALIDATION_FAILED', 422);
        }

        $salon = $this->findSalonOrFail($this->salonRepository, $salonId);
        $this->assertSalonBookable($salon);

        $services = $this->serviceRepository->findActiveByIdsForSalon($serviceIds, $salonId);

        if ($services->count() !== count(array_unique($serviceIds))) {
            throw new BusinessException('Dịch vụ không hợp lệ.', 'INVALID_SERVICES');
        }

        return AvailableSlotsCache::remember(
            $salonId,
            $filters,
            fn () => $this->computeAvailableSlots($salonId, $date, $services, $filters)
        );
    }

    protected function computeAvailableSlots(
        string $salonId,
        string $date,
        \Illuminate\Support\Collection $services,
        array $filters
    ): array {
        $resolved = ServiceStyleOptionResolver::resolveLines(
            $services,
            $filters['style_options'] ?? []
        );
        $duration = $resolved['total_duration'];
        $salonSettings = $this->salonSettingsService->getForSalon($salonId);
        $intervalMinutes = $salonSettings->booking_interval_minutes;
        $staffForServices = $this->staffRepository->getActiveForSalonWithServices($salonId)
            ->filter(fn (Staff $staff) => $this->staffRepository->staffProvidesServices($staff, $services->pluck('id')))
            ->values();

        if ($staffForServices->isEmpty()) {
            return $this->emptyAvailableSlotsResponse('no_staff_for_services');
        }

        $scheduleMap = $this->staffScheduleRepository->getForStaffIdsOnDate(
            $staffForServices->pluck('id')->all(),
            $date,
            \App\Models\StaffSchedule::STATUS_APPROVED
        )->keyBy('staff_id');

        $staffMembers = $staffForServices
            ->filter(fn (Staff $staff) => $scheduleMap->has($staff->id))
            ->values();

        if ($staffMembers->isEmpty()) {
            return $this->emptyAvailableSlotsResponse('no_staff_schedule');
        }

        $salon = $this->findSalonOrFail($this->salonRepository, $salonId);
        $salonOpen = $this->timeToMinutes(substr((string) $salon->open_time, 0, 5));
        $salonClose = $this->timeToMinutes(substr((string) $salon->close_time, 0, 5));
        $dayBookings = $this->ownerBookingRepository->getDayBookingsForSalon($salonId, $date);

        $slots = [];
        $availableTimes = [];

        foreach ($staffMembers as $staff) {
            $schedule = $scheduleMap->get($staff->id);
            $windowStart = max(
                $salonOpen,
                $this->timeToMinutes(substr((string) $schedule->start_time, 0, 5))
            );
            $windowEnd = min(
                $salonClose,
                $this->timeToMinutes(substr((string) $schedule->end_time, 0, 5))
            );

            $times = $this->generateTimeOptionsBetween($windowStart, $windowEnd, $duration, $intervalMinutes);
            $staffBookings = $dayBookings->where('staff_id', $staff->id);

            foreach ($times as $time) {
                $normalizedTime = $time . ':00';

                $isAvailable = ! $this->hasStaffScheduleConflict(
                    $staffBookings,
                    $normalizedTime,
                    $duration,
                    $staff->id
                );

                $slots[] = [
                    'staff_id' => $staff->id,
                    'time' => $time,
                    'status' => $isAvailable ? 'available' : 'booked',
                ];

                if ($isAvailable && ! in_array($time, $availableTimes, true)) {
                    $availableTimes[] = $time;
                }
            }
        }

        sort($availableTimes);

        $availability = $availableTimes === [] ? 'no_available_slots' : null;

        return [
            'staff' => $staffMembers->map(fn ($s) => ['id' => $s->id, 'name' => $s->name])->values(),
            'times' => array_values($availableTimes),
            'slots' => array_values(array_filter(
                $slots,
                fn (array $slot) => $slot['status'] === 'available'
            )),
            'availability' => $availability,
            'booking_interval_minutes' => $intervalMinutes,
        ];
    }

    protected function emptyAvailableSlotsResponse(string $reason): array
    {
        return [
            'staff' => [],
            'times' => [],
            'slots' => [],
            'availability' => $reason,
        ];
    }

    protected function invalidateAvailableSlotsForBooking(Booking $booking, ?string $previousDate = null): void
    {
        $salonId = $booking->salon_id;
        $currentDate = $this->bookingDateString($booking);

        AvailableSlotsCache::forgetSalonDate($salonId, $currentDate);

        if ($previousDate !== null && $previousDate !== $currentDate) {
            AvailableSlotsCache::forgetSalonDate($salonId, $previousDate);
        }
    }

    protected function bookingDateString(Booking $booking): string
    {
        return $booking->booking_date->toDateString();
    }

    protected function resolveSalonSeats(string $salonId): \Illuminate\Support\Collection
    {
        $seats = $this->seatRepository->getActiveBySalon($salonId);

        if ($seats->isNotEmpty()) {
            return $seats;
        }

        $seat = $this->seatRepository->create([
            'salon_id' => $salonId,
            'name' => 'Ghế 1',
            'is_active' => true,
        ]);

        return collect([$seat]);
    }

    protected function generateTimeOptions(Salon $salon, int $durationMinutes, int $intervalMinutes = 30): array
    {
        $open = $this->timeToMinutes(substr((string) $salon->open_time, 0, 5));
        $close = $this->timeToMinutes(substr((string) $salon->close_time, 0, 5));

        return $this->generateTimeOptionsBetween($open, $close, $durationMinutes, $intervalMinutes);
    }

    protected function findBookingOrFail(string $id, array $relations = []): Booking
    {
        $defaultRelations = BookingRepository::detailRelations();

        foreach ($defaultRelations as $index => $relation) {
            if (is_string($relation) && str_starts_with($relation, 'salon:')) {
                $defaultRelations[$index] = 'salon:id,name,address,owner_id,open_time,close_time,approval_status,is_locked,status';
                break;
            }
        }

        $defaultRelations[] = 'staff:id,name,salon_id';

        $booking = $this->bookingRepository->findById($id, array_merge($defaultRelations, $relations));

        if (! $booking) {
            throw new BusinessException('Booking không tồn tại.', 'BOOKING_NOT_FOUND', 404);
        }

        return $booking;
    }

    protected function assertCanViewBooking(Booking $booking, ?User $actor): void
    {
        if (! $actor) {
            throw new BusinessException('Unauthorized.', 'UNAUTHENTICATED', 401);
        }

        if ($actor->isAdmin()) {
            return;
        }

        if ($actor->isCustomer() && $booking->customer_id === $actor->id) {
            return;
        }

        if ($actor->isOwner() && $booking->salon?->owner_id === $actor->id) {
            return;
        }

        throw new BusinessException('Không có quyền xem booking này.', 'FORBIDDEN', 403);
    }

    protected function assertCanManageBooking(Booking $booking, User $actor): void
    {
        if ($actor->isAdmin()) {
            return;
        }

        if ($actor->isOwner() && $booking->salon?->owner_id === $actor->id) {
            return;
        }

        throw new BusinessException('Không có quyền quản lý booking này.', 'FORBIDDEN', 403);
    }
}
