<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        $bookings = Booking::query()
            ->with('payment')
            ->whereIn('status', [
                Booking::STATUS_PENDING,
                Booking::STATUS_CONFIRMED,
                Booking::STATUS_COMPLETED,
                Booking::STATUS_CANCELLED,
                Booking::STATUS_NO_SHOW,
            ])
            ->get();

        $created = 0;

        foreach ($bookings as $index => $booking) {
            $status = $this->resolvePaymentStatus($booking->status, $index);
            if ($status === null) {
                continue;
            }

            $payload = [
                'method' => $this->resolveMethod($index),
                'amount' => (int) $booking->total_price,
                'status' => $status,
                'provider_transaction_id' => $status === Payment::STATUS_PAID
                    ? 'DEMO-TXN-'.substr((string) $booking->id, 0, 8)
                    : null,
                'paid_at' => in_array($status, [Payment::STATUS_PAID, Payment::STATUS_REFUNDED], true)
                    ? now()->subDays($index % 14)
                    : null,
                'refunded_at' => $status === Payment::STATUS_REFUNDED
                    ? now()->subDays(max(0, ($index % 7) - 1))
                    : null,
            ];

            Payment::query()->updateOrCreate(
                ['booking_id' => $booking->id],
                $payload,
            );

            $created++;
        }

        $this->command?->info("Seeded {$created} payments.");
    }

    private function resolvePaymentStatus(string $bookingStatus, int $index): ?string
    {
        return match ($bookingStatus) {
            Booking::STATUS_COMPLETED => $index % 9 === 0
                ? Payment::STATUS_REFUNDED
                : Payment::STATUS_PAID,
            Booking::STATUS_CONFIRMED => $index % 2 === 0
                ? Payment::STATUS_PAID
                : Payment::STATUS_UNPAID,
            Booking::STATUS_PENDING => Payment::STATUS_UNPAID,
            Booking::STATUS_CANCELLED => $index % 4 === 0 ? Payment::STATUS_FAILED : null,
            Booking::STATUS_NO_SHOW => $index % 3 === 0 ? Payment::STATUS_UNPAID : null,
            default => null,
        };
    }

    private function resolveMethod(int $index): string
    {
        $methods = [
            Payment::METHOD_CASH,
            Payment::METHOD_MOMO,
            Payment::METHOD_ZALOPAY,
            Payment::METHOD_BANK_TRANSFER,
            Payment::METHOD_CARD,
        ];

        return $methods[$index % count($methods)];
    }
}
