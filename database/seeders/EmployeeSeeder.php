<?php

namespace Database\Seeders;

use App\Models\Seat;
use App\Models\Staff;
use App\Models\StaffSchedule;
use Database\Seeders\Data\DemoEmployeesData;
use Database\Seeders\Data\DemoSalonsData;
use Database\Seeders\Support\DemoSeederConstants;
use Database\Seeders\Support\SalonLookup;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        foreach (DemoSalonsData::all() as $index => $entry) {
            $salon = SalonLookup::salonAt($index);
            $this->seedSeats($salon->id, $entry['seat_count']);
            $this->seedEmployees($salon->id, $index);
        }
    }

    private function seedSeats(string $salonId, int $seatCount): void
    {
        for ($i = 1; $i <= $seatCount; $i++) {
            Seat::query()->create([
                'salon_id' => $salonId,
                'name' => "Ghế {$i}",
                'is_active' => true,
            ]);
        }
    }

    private function seedEmployees(string $salonId, int $salonIndex): void
    {
        $team = DemoEmployeesData::teamForSalon($salonIndex);

        foreach ($team as $memberIndex => $member) {
            $staff = Staff::query()->create([
                'salon_id' => $salonId,
                'name' => $member['name'],
                'avatar_url' => $member['avatar_url'],
                'bio' => "Giới tính: {$member['gender']} | Chuyên môn: {$member['specialties']}",
                'experience_years' => $member['experience_years'],
                'is_active' => true,
            ]);

            $serviceIds = [];
            foreach ($member['service_names'] as $serviceName) {
                $service = SalonLookup::serviceByName($salonId, $serviceName);
                $serviceIds[] = $service->id;
            }

            $staff->services()->sync(array_values(array_unique($serviceIds)));
            $this->seedStaffSchedules($staff, $memberIndex);
        }
    }

    private function seedStaffSchedules(Staff $staff, int $memberIndex): void
    {
        $shift = DemoSeederConstants::STAFF_SHIFT_PATTERNS[$memberIndex % count(DemoSeederConstants::STAFF_SHIFT_PATTERNS)];

        foreach (DemoSeederConstants::STAFF_SCHEDULE_DATES as $workDate) {
            StaffSchedule::query()->create([
                'staff_id' => $staff->id,
                'work_date' => $workDate,
                'start_time' => $shift['start_time'],
                'end_time' => $shift['end_time'],
                'status' => StaffSchedule::STATUS_APPROVED,
                'submitted_by' => StaffSchedule::SUBMITTED_BY_OWNER,
            ]);
        }
    }
}
