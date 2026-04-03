<?php

namespace Database\Seeders\Support;

use App\Models\Booking;
use App\Models\BookingService;
use App\Models\Payment;
use App\Models\Salon;
use App\Models\Seat;
use App\Models\Service;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Support\Collection;

final class DemoBookingGenerator
{
    /** @var array<string, list<array{0: int, 1: int}>> */
    private array $staffDayIntervals = [];

    public function __construct(
        private readonly Salon $salon,
        private readonly Collection $staffMembers,
        private readonly Collection $services,
        private readonly Collection $seats,
        private readonly Collection $customers,
    ) {}

    public function purgeGenerated(): void
    {
        $bookings = Booking::query()
            ->with('bookingServices')
            ->where('salon_id', $this->salon->id)
            ->where('customer_notes', 'like', DemoPrimarySalon::GENERATED_BOOKING_NOTE_PREFIX.'%')
            ->get();

        if ($bookings->isEmpty()) {
            return;
        }

        foreach ($bookings as $booking) {
            foreach ($booking->bookingServices as $lineItem) {
                Service::query()
                    ->where('id', $lineItem->service_id)
                    ->decrement('bookings_count');
            }

            Salon::query()->where('id', $booking->salon_id)->decrement('bookings_count');
        }

        $bookingIds = $bookings->pluck('id');
        Payment::query()->whereIn('booking_id', $bookingIds)->delete();
        BookingService::query()->whereIn('booking_id', $bookingIds)->delete();
        Booking::query()->whereIn('id', $bookingIds)->forceDelete();
    }

    public function generate(int $count): int
    {
        $this->purgeGenerated();
        $this->staffDayIntervals = [];

        $dates = $this->buildDatePool($count);

        $statusPool = $this->buildStatusPool($count);
        $customerPool = $this->buildCustomerPool($count);
        $created = 0;
        $attempts = 0;
        $maxAttempts = $count * 25;

        while ($created < $count && $attempts < $maxAttempts) {
            $attempts++;
            $status = $statusPool[$created % count($statusPool)];
            $date = $dates[$created % count($dates)];
            $staff = $this->staffMembers->get($created % $this->staffMembers->count());
            $service = $this->services->random();
            $duration = DemoPrimarySalon::BOOKING_DURATIONS[$created % count(DemoPrimarySalon::BOOKING_DURATIONS)];
            $slot = $this->pickAvailableSlot($staff->id, $date, $duration, $created);

            if ($slot === null) {
                continue;
            }

            /** @var User $customer */
            $customer = $customerPool[$created % count($customerPool)];
            $seat = $this->seats->get($created % $this->seats->count());
            $noteTemplate = DemoPrimarySalon::CUSTOMER_NOTES[$created % count(DemoPrimarySalon::CUSTOMER_NOTES)];
            $note = DemoPrimarySalon::GENERATED_BOOKING_NOTE_PREFIX.' '.($noteTemplate ?? 'Demo booking');

            $booking = Booking::query()->create([
                'salon_id' => $this->salon->id,
                'customer_id' => $customer->id,
                'staff_id' => $staff->id,
                'seat_id' => $seat->id,
                'booking_date' => $date,
                'booking_time' => $slot,
                'status' => $status,
                'total_price' => (int) $service->price,
                'total_duration_minutes' => $duration,
                'customer_notes' => $note,
                'cancel_reason' => $status === Booking::STATUS_CANCELLED ? 'Khách hủy trước giờ hẹn' : null,
                'has_reviewed' => $status === Booking::STATUS_COMPLETED && ($created % 3 === 0),
                'created_by' => $customer->id,
            ]);

            BookingService::query()->create([
                'booking_id' => $booking->id,
                'service_id' => $service->id,
                'service_style_option_id' => null,
                'price' => (int) $service->price,
                'duration_minutes' => $duration,
                'sort_order' => 0,
            ]);

            $service->increment('bookings_count');
            $this->salon->increment('bookings_count');
            $created++;
        }

        return $created;
    }

    /**
     * @return list<string>
     */
    private function buildDatePool(int $count): array
    {
        $pool = [];

        foreach (DemoDateHelper::range(-1, 7) as $date) {
            $pool = array_merge($pool, array_fill(0, 8, $date));
        }

        foreach (DemoDateHelper::historyWindow(40) as $date) {
            $pool[] = $date;
        }

        foreach (DemoDateHelper::range(8, 14) as $date) {
            $pool[] = $date;
        }

        while (count($pool) < $count) {
            $pool[] = DemoDateHelper::range(-1, 7)[count($pool) % 9];
        }

        return $pool;
    }

    /**
     * @return list<string>
     */
    private function buildStatusPool(int $count): array
    {
        $weights = [
            Booking::STATUS_PENDING => 12,
            Booking::STATUS_CONFIRMED => 28,
            Booking::STATUS_COMPLETED => 40,
            Booking::STATUS_CANCELLED => 12,
            Booking::STATUS_NO_SHOW => 8,
        ];

        $pool = [];
        foreach ($weights as $status => $weight) {
            $pool = array_merge($pool, array_fill(0, $weight, $status));
        }

        while (count($pool) < $count) {
            $pool[] = Booking::STATUS_CONFIRMED;
        }

        return $pool;
    }

    /**
     * @return list<User>
     */
    private function buildCustomerPool(int $count): array
    {
        $customers = $this->customers->values()->all();
        if ($customers === []) {
            return [];
        }

        $pool = [];
        $weights = [10, 8, 6, 5, 4, 3, 3, 2, 2, 1];

        foreach ($customers as $index => $customer) {
            $repeat = $weights[$index] ?? 1;
            for ($i = 0; $i < $repeat; $i++) {
                $pool[] = $customer;
            }
        }

        while (count($pool) < $count) {
            $pool[] = $customers[$index % count($customers)];
        }

        return $pool;
    }

    private function pickAvailableSlot(string $staffId, string $date, int $duration, int $seed): ?string
    {
        $candidates = DemoSeederConstants::SLOT_TIMES;

        usort($candidates, fn ($a, $b) => crc32("{$staffId}|{$date}|{$seed}|{$a}") <=> crc32("{$staffId}|{$date}|{$seed}|{$b}"));

        foreach ($candidates as $slot) {
            $start = $this->timeToMinutes($slot);
            $end = $start + $duration;

            if ($start < 8 * 60 || $end > 20 * 60) {
                continue;
            }

            if ($this->hasOverlap($staffId, $date, $start, $end)) {
                continue;
            }

            $this->occupy($staffId, $date, $start, $end);

            return $slot;
        }

        return null;
    }

    private function hasOverlap(string $staffId, string $date, int $start, int $end): bool
    {
        $key = "{$staffId}|{$date}";

        foreach ($this->staffDayIntervals[$key] ?? [] as [$busyStart, $busyEnd]) {
            if ($start < $busyEnd && $end > $busyStart) {
                return true;
            }
        }

        return false;
    }

    private function occupy(string $staffId, string $date, int $start, int $end): void
    {
        $key = "{$staffId}|{$date}";
        $this->staffDayIntervals[$key][] = [$start, $end];
    }

    private function timeToMinutes(string $time): int
    {
        [$hour, $minute] = array_map('intval', explode(':', substr($time, 0, 5)));

        return ($hour * 60) + $minute;
    }
}
