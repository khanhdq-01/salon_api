<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Review;
use App\Models\Salon;
use Database\Seeders\Data\DemoReviewsData;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $created = 0;

        foreach (DemoReviewsData::all() as $reviewData) {
            $bookingId = BookingSeeder::$bookingIdsByKey[$reviewData['booking_key']] ?? null;

            if ($bookingId === null) {
                continue;
            }

            $booking = Booking::query()->findOrFail($bookingId);

            $review = Review::query()->create([
                'booking_id' => $booking->id,
                'salon_id' => $booking->salon_id,
                'customer_id' => $booking->customer_id,
                'rating' => $reviewData['rating'],
                'comment' => $reviewData['comment'],
            ]);

            $review->forceFill(['created_at' => $reviewData['created_at']])->save();
            $booking->update(['has_reviewed' => true]);
            $created++;
        }

        $salonStats = Review::query()
            ->selectRaw('salon_id, AVG(rating) as avg_rating, COUNT(*) as total')
            ->groupBy('salon_id')
            ->get();

        foreach ($salonStats as $stat) {
            Salon::query()
                ->where('id', $stat->salon_id)
                ->update([
                    'rating_avg' => round((float) $stat->avg_rating, 2),
                    'rating_count' => (int) $stat->total,
                ]);
        }

        $this->command?->info("Seeded {$created} salon reviews.");
    }
}
