<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\CustomerNotification;
use App\Models\OwnerNotificationBroadcast;
use Database\Seeders\Data\DemoNotificationsData;
use Database\Seeders\Support\SalonLookup;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        $broadcastCount = 0;
        $customerNotificationCount = 0;

        foreach (DemoNotificationsData::all() as $entry) {
            $salon = SalonLookup::salonAt($entry['salon_index']);
            $publishedAt = $entry['published_at'];

            $broadcast = OwnerNotificationBroadcast::query()->create([
                'salon_id' => $salon->id,
                'owner_id' => $salon->owner_id,
                'type' => $entry['type'],
                'title' => $entry['title'],
                'content' => $entry['content'],
                'recipient_count' => 0,
                'scheduled_at' => $publishedAt,
                'sent_at' => $publishedAt,
            ]);
            $broadcast->forceFill(['created_at' => $publishedAt])->save();
            $broadcastCount++;

            $customerIds = Booking::query()
                ->where('salon_id', $salon->id)
                ->whereNotNull('customer_id')
                ->distinct()
                ->pluck('customer_id');

            foreach ($customerIds as $customerId) {
                $notification = CustomerNotification::query()->create([
                    'user_id' => $customerId,
                    'salon_id' => $salon->id,
                    'broadcast_id' => $broadcast->id,
                    'type' => $entry['type'],
                    'title' => $entry['title'],
                    'content' => $entry['content'],
                    'read_at' => null,
                ]);
                $notification->forceFill(['created_at' => $publishedAt])->save();
                $customerNotificationCount++;
            }
        }

        $this->command?->info("Seeded {$broadcastCount} shop broadcasts and {$customerNotificationCount} customer notifications.");
    }
}
