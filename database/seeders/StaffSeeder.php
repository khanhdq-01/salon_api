<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Seat;
use App\Models\Staff;
use App\Models\StaffSchedule;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Database\Seeders\Concerns\SeedsIdempotentUsers;
use Database\Seeders\Data\DemoEmployeesData;
use Database\Seeders\Data\DemoSalonsData;
use Database\Seeders\Support\DemoPrimarySalon;
use Database\Seeders\Support\DemoSeederConstants;
use Database\Seeders\Support\DemoStaffAccountHelper;
use Database\Seeders\Support\SalonLookup;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class StaffSeeder extends Seeder
{
    use SeedsIdempotentUsers;

    private int $linkedStaffAccounts = 0;

    public function run(): void
    {
        $this->ensureStaffRoleExists();
        $this->linkedStaffAccounts = 0;

        foreach (DemoSalonsData::all() as $index => $entry) {
            $salon = SalonLookup::salonAt($index);
            $this->seedSeats($salon->id, (int) $entry['seat_count']);
            $this->seedEmployees($salon->id, $index, $entry['owner_email']);
        }

        $this->command?->info("Seeded {$this->linkedStaffAccounts} staff accounts with email and phone.");
        $this->command?->info(
            'Primary staff portal: '.implode(', ', DemoPrimarySalon::STAFF_ACCOUNTS)
            .' (password '.DemoSeederConstants::PASSWORD.')'
        );
    }

    private function seedSeats(string $salonId, int $seatCount): void
    {
        for ($i = 1; $i <= $seatCount; $i++) {
            Seat::query()->firstOrCreate(
                ['salon_id' => $salonId, 'name' => "Ghế {$i}"],
                ['is_active' => true],
            );
        }
    }

    private function seedEmployees(string $salonId, int $salonIndex, string $ownerEmail): void
    {
        $owner = User::query()->where('email', $ownerEmail)->first();
        $team = DemoEmployeesData::teamForSalon($salonIndex);

        foreach ($team as $memberIndex => $member) {
            $staff = Staff::query()->firstOrCreate(
                ['salon_id' => $salonId, 'name' => $member['name']],
                [
                    'avatar_url' => $member['avatar_url'],
                    'bio' => "Giới tính: {$member['gender']} | Chuyên môn: {$member['specialties']}",
                    'experience_years' => $member['experience_years'],
                    'is_active' => true,
                ],
            );

            $serviceIds = [];
            foreach ($member['service_names'] as $serviceName) {
                $service = SalonLookup::serviceByName($salonId, $serviceName);
                $serviceIds[] = $service->id;
            }

            $staff->services()->sync(array_values(array_unique($serviceIds)));

            if ($owner) {
                $this->linkStaffAccount($staff, $owner, $salonIndex, $memberIndex, $member['name']);
            }

            // Seed work schedules cho tháng 7 và 8
            $this->seedStaffSchedules($staff, $salonIndex);
        }
    }

    private function linkStaffAccount(
        Staff $staff,
        User $owner,
        int $salonIndex,
        int $memberIndex,
        string $name,
    ): void {
        $account = DemoStaffAccountHelper::accountFor($salonIndex, $memberIndex, $name);

        $user = $this->seedUser([
            'role_id' => Role::ID_STAFF,
            'name' => $staff->name,
            'email' => $account['email'],
            'phone' => $account['phone'],
            'owner_id' => $owner->id,
            'last_login_days_ago' => $account['last_login_days_ago'],
        ]);

        Staff::query()
            ->where('user_id', $user->id)
            ->where('id', '!=', $staff->id)
            ->update(['user_id' => null]);

        if ($staff->user_id !== $user->id) {
            $staff->update(['user_id' => $user->id]);
        }

        $this->linkedStaffAccounts++;
    }

    /**
     * Seed work schedules cho nhân viên: tháng 7 và 8, khung giờ công việc.
     * Từ thứ 2 đến thứ 6, 9:00-17:00 với một ngày nghỉ ngẫu nhiên/tuần.
     */
    private function seedStaffSchedules(Staff $staff, int $salonIndex): void
    {
        $startDate = Carbon::createFromDate(2026, 7, 1); // July 1, 2026
        $endDate = Carbon::createFromDate(2026, 8, 31); // August 31, 2026

        $current = $startDate->clone();
        $skipDayInWeek = ($salonIndex % 5); // Mỗi salon có một ngày nghỉ khác nhau trong tuần

        while ($current->lte($endDate)) {
            $dayOfWeek = $current->dayOfWeek; // 0 = Sunday, 1 = Monday, ..., 6 = Saturday

            // Chỉ tạo schedule từ thứ 2-6 (dayOfWeek 1-5)
            if ($dayOfWeek >= 1 && $dayOfWeek <= 5) {
                // Nếu đó là ngày được chỉ định để nghỉ trong tuần, bỏ qua
                if ($dayOfWeek - 1 != $skipDayInWeek) {
                    StaffSchedule::query()->firstOrCreate(
                        [
                            'staff_id' => $staff->id,
                            'work_date' => $current->toDateString(),
                        ],
                        [
                            'start_time' => '09:00:00',
                            'end_time' => '17:00:00',
                            'status' => StaffSchedule::STATUS_APPROVED,
                            'submitted_by' => StaffSchedule::SUBMITTED_BY_OWNER,
                            'approved_at' => now(),
                        ],
                    );
                }
            }

            $current->addDay();
        }
    }

    private function ensureStaffRoleExists(): void
    {
        if (DB::table('roles')->where('id', Role::ID_STAFF)->exists()) {
            return;
        }

        DB::table('roles')->insert([
            'id' => Role::ID_STAFF,
            'name' => Role::STAFF,
            'display_name' => 'Nhân viên',
            'description' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
