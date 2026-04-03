<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\BookingService;
use App\Models\Role;
use App\Models\Seat;
use App\Models\Service;
use App\Models\Staff;
use App\Models\User;
use Database\Seeders\Data\DemoBookingsData;
use Database\Seeders\Support\DemoBookingGenerator;
use Database\Seeders\Support\DemoPrimarySalon;
use Database\Seeders\Support\SalonLookup;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    /** @var array<string, string> */
    public static array $bookingIdsByKey = [];

    public function run(): void
    {
        self::$bookingIdsByKey = [];

        $staticCount = $this->seedStaticBookings();
        $generatedCount = $this->seedPrimarySalonGeneratedBookings();

        $this->command?->info("Seeded {$staticCount} static + {$generatedCount} generated bookings.");
    }

    private function seedStaticBookings(): int
    {
        $created = 0;
        $salonBookingCounters = [];

        foreach (DemoBookingsData::all() as $entry) {
            $salonIndex = $entry['salon_index'];
            $bookingIndex = $salonBookingCounters[$salonIndex] ?? 0;
            $bookingKey = "{$salonIndex}_{$bookingIndex}";

            $salon = SalonLookup::salonAt($salonIndex);
            $customer = SalonLookup::customerByEmail($entry['customer_email']);
            $staff = $this->resolveStaffForBooking($salon->id, $entry['staff_name']);
            $seat = Seat::query()
                ->where('salon_id', $salon->id)
                ->where('name', $entry['seat_name'])
                ->firstOrFail();
            $service = SalonLookup::serviceByName($salon->id, $entry['service_name']);
            $styleOption = $entry['style_name']
                ? SalonLookup::styleByName($service->id, $entry['style_name'])
                : null;

            $price = (int) $service->price + (int) ($styleOption?->extra_price ?? 0);
            $duration = (int) $service->duration_minutes + (int) ($styleOption?->extra_duration ?? 0);

            $existing = Booking::query()
                ->where('salon_id', $salon->id)
                ->where('customer_id', $customer->id)
                ->where('staff_id', $staff->id)
                ->where('booking_date', $entry['booking_date'])
                ->where('booking_time', $entry['booking_time'])
                ->first();

            if ($existing) {
                self::$bookingIdsByKey[$bookingKey] = $existing->id;
                $salonBookingCounters[$salonIndex] = $bookingIndex + 1;

                continue;
            }

            $booking = Booking::query()->create([
                'salon_id' => $salon->id,
                'customer_id' => $customer->id,
                'staff_id' => $staff->id,
                'seat_id' => $seat->id,
                'booking_date' => $entry['booking_date'],
                'booking_time' => $entry['booking_time'],
                'status' => $entry['status'],
                'total_price' => $price,
                'total_duration_minutes' => $duration,
                'customer_notes' => $entry['customer_notes'],
                'cancel_reason' => $entry['cancel_reason'],
                'has_reviewed' => $entry['has_reviewed'],
                'created_by' => $customer->id,
            ]);

            BookingService::query()->create([
                'booking_id' => $booking->id,
                'service_id' => $service->id,
                'service_style_option_id' => $styleOption?->id,
                'price' => $price,
                'duration_minutes' => $duration,
                'sort_order' => 0,
            ]);

            $service->increment('bookings_count');
            $salon->increment('bookings_count');

            self::$bookingIdsByKey[$bookingKey] = $booking->id;
            $salonBookingCounters[$salonIndex] = $bookingIndex + 1;
            $created++;
        }

        return $created;
    }

    private function seedPrimarySalonGeneratedBookings(): int
    {
        $salon = SalonLookup::salonAt(DemoPrimarySalon::SALON_INDEX);
        $staffMembers = Staff::query()
            ->where('salon_id', $salon->id)
            ->orderBy('name')
            ->get();
        $services = Service::query()
            ->where('salon_id', $salon->id)
            ->where('is_active', true)
            ->get();
        $seats = Seat::query()
            ->where('salon_id', $salon->id)
            ->orderBy('name')
            ->get();
        $customers = User::query()
            ->where('role_id', Role::ID_CUSTOMER)
            ->orderBy('email')
            ->limit(20)
            ->get();

        $generator = new DemoBookingGenerator(
            $salon,
            $staffMembers,
            $services,
            $seats,
            $customers,
        );

        return $generator->generate(DemoPrimarySalon::TARGET_BOOKING_COUNT);
    }

    private function resolveStaffForBooking(string $salonId, string $staffName): Staff
    {
        $staff = Staff::query()
            ->where('salon_id', $salonId)
            ->where('name', $staffName)
            ->first();

        if ($staff) {
            return $staff;
        }

        return Staff::query()
            ->where('salon_id', $salonId)
            ->orderBy('name')
            ->firstOrFail();
    }
}
