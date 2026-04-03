<?php

namespace Database\Seeders;

use App\Models\Staff;
use App\Models\StaffSchedule;
use Database\Seeders\Data\DemoSalonsData;
use Database\Seeders\Support\DemoDateHelper;
use Database\Seeders\Support\DemoPrimarySalon;
use Database\Seeders\Support\DemoSeederConstants;
use Database\Seeders\Support\SalonLookup;
use Illuminate\Database\Seeder;

class WorkingScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedPrimarySalonSchedules();
        $this->seedOtherSalonSchedules();
    }

    private function seedPrimarySalonSchedules(): void
    {
        $salon = SalonLookup::salonAt(DemoPrimarySalon::SALON_INDEX);
        $staffMembers = Staff::query()
            ->where('salon_id', $salon->id)
            ->orderBy('name')
            ->get();

        $dates = DemoDateHelper::range(-7, 14);

        foreach ($staffMembers as $staffIndex => $staff) {
            $shift = DemoPrimarySalon::STAFF_SHIFTS[$staffIndex % count(DemoPrimarySalon::STAFF_SHIFTS)];

            foreach ($dates as $dayIndex => $workDate) {
                $pattern = ($staffIndex + $dayIndex) % 7;

                if ($pattern === 6) {
                    continue;
                }

                $startTime = $shift['start_time'];
                $endTime = $shift['end_time'];
                $status = StaffSchedule::STATUS_APPROVED;
                $submittedBy = StaffSchedule::SUBMITTED_BY_OWNER;

                if ($pattern === 5) {
                    $endTime = '12:00:00';
                }

                if ($dayIndex % 11 === 0 && $staffIndex % 2 === 0) {
                    $status = StaffSchedule::STATUS_PENDING;
                    $submittedBy = StaffSchedule::SUBMITTED_BY_STAFF;
                }

                if ($dayIndex % 13 === 0 && $staffIndex % 3 === 0) {
                    $status = StaffSchedule::STATUS_REJECTED;
                    $submittedBy = StaffSchedule::SUBMITTED_BY_STAFF;
                }

                StaffSchedule::query()->updateOrCreate(
                    [
                        'staff_id' => $staff->id,
                        'work_date' => $workDate,
                    ],
                    [
                        'start_time' => $startTime,
                        'end_time' => $endTime,
                        'status' => $status,
                        'submitted_by' => $submittedBy,
                        'note' => $shift['label'],
                    ],
                );
            }
        }

        $this->command?->info('Seeded dynamic work schedules for primary salon ('.count($dates).' days / staff).');
    }

    private function seedOtherSalonSchedules(): void
    {
        foreach (DemoSalonsData::all() as $index => $entry) {
            if ($index === DemoPrimarySalon::SALON_INDEX) {
                continue;
            }

            $salon = SalonLookup::salonAt($index);
            $staffMembers = Staff::query()->where('salon_id', $salon->id)->orderBy('name')->get();
            $dates = DemoDateHelper::range(0, 13);

            foreach ($staffMembers as $memberIndex => $staff) {
                $shift = DemoSeederConstants::STAFF_SHIFT_PATTERNS[$memberIndex % count(DemoSeederConstants::STAFF_SHIFT_PATTERNS)];

                foreach ($dates as $dayIndex => $workDate) {
                    if (($memberIndex + $dayIndex) % 9 === 0) {
                        continue;
                    }

                    StaffSchedule::query()->updateOrCreate(
                        [
                            'staff_id' => $staff->id,
                            'work_date' => $workDate,
                        ],
                        [
                            'start_time' => $shift['start_time'],
                            'end_time' => $shift['end_time'],
                            'status' => StaffSchedule::STATUS_APPROVED,
                            'submitted_by' => StaffSchedule::SUBMITTED_BY_OWNER,
                        ],
                    );
                }
            }
        }
    }
}
