<?php

namespace Database\Seeders;

use App\Models\Package;
use App\Models\Salon;
use App\Models\Subscription;
use App\Models\User;
use Database\Seeders\Concerns\SeedsSalonSchedules;
use Database\Seeders\Data\DemoSalonsData;
use Database\Seeders\Support\DemoSeederConstants;
use Illuminate\Database\Seeder;

class SalonSeeder extends Seeder
{
    use SeedsSalonSchedules;

    public function run(): void
    {
        $packages = Package::query()->orderBy('created_at')->get();
        $admin = User::query()->where('email', DemoSeederConstants::ADMIN_EMAIL)->firstOrFail();

        foreach (DemoSalonsData::all() as $entry) {
            $owner = User::query()->where('email', $entry['owner_email'])->firstOrFail();
            $package = $packages[$entry['package_index'] % $packages->count()];
            $salonData = $entry['salon'];

            $salon = Salon::query()->create([
                'owner_id' => $owner->id,
                'requested_package_id' => $package->id,
                'name' => $salonData['name'],
                'slug' => $entry['slug'],
                'description' => "Salon tóc chuyên nghiệp tại {$salonData['city']}. Đặt lịch online, chọn stylist và dịch vụ phù hợp.",
                'address' => $salonData['address'],
                'lat' => $salonData['lat'],
                'lng' => $salonData['lng'],
                'phone' => $salonData['phone'],
                'image_url' => $salonData['image'],
                'open_time' => '08:00:00',
                'close_time' => '20:00:00',
                'status' => Salon::STATUS_OPEN,
                'approval_status' => Salon::APPROVAL_APPROVED,
                'is_locked' => false,
                'rating_avg' => 0,
                'rating_count' => 0,
                'bookings_count' => 0,
            ]);

            $this->seedSalonWeeklySchedule($salon);
            $this->seedActiveSubscription($owner, $package, $admin);
        }
    }

    private function seedActiveSubscription(User $owner, Package $package, User $admin): void
    {
        $startDate = now()->subDays(15);
        $endDate = $package->calculateEndDate($startDate);

        Subscription::query()->create([
            'owner_id' => $owner->id,
            'package_id' => $package->id,
            'status' => Subscription::STATUS_ACTIVE,
            'start_date' => $startDate->toDateString(),
            'end_date' => $endDate->toDateString(),
            'auto_renew' => true,
            'approved_amount' => $package->price,
            'approved_at' => now()->subDays(14),
            'approved_by' => $admin->id,
        ]);
    }
}
