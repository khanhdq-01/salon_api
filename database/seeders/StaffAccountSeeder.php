<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Staff;
use App\Models\User;
use App\Support\StaffAccountProvisioner;
use Database\Seeders\Support\DemoSeederConstants;
use Database\Seeders\Support\SalonLookup;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StaffAccountSeeder extends Seeder
{
    /** @var list<array{email: string, name: string, phone: string}> */
    private const DEMO_ACCOUNTS = [
        ['email' => 'staff1@gmail.com', 'name' => 'Staff Demo 1', 'phone' => '0909000001'],
        ['email' => 'staff2@gmail.com', 'name' => 'Staff Demo 2', 'phone' => '0909000002'],
        ['email' => 'staff3@gmail.com', 'name' => 'Staff Demo 3', 'phone' => '0909000003'],
    ];

    public function run(): void
    {
        $this->ensureStaffRoleExists();

        $owner = User::query()
            ->where('email', 'owner@gmail.com')
            ->where('role_id', Role::ID_OWNER)
            ->first();

        if (! $owner) {
            $owner = User::query()
                ->where('role_id', Role::ID_OWNER)
                ->orderBy('email')
                ->first();
        }

        if (! $owner) {
            $this->command?->warn('StaffAccountSeeder: no owner found.');

            return;
        }

        if (
            $owner->email !== 'owner@gmail.com'
            && ! User::query()->where('email', 'owner@gmail.com')->exists()
        ) {
            $owner->update(['email' => 'owner@gmail.com']);
        }

        $salon = SalonLookup::salonAt(0);
        $staffProfiles = Staff::query()
            ->where('salon_id', $salon->id)
            ->whereNull('user_id')
            ->orderBy('name')
            ->limit(count(self::DEMO_ACCOUNTS))
            ->get();

        foreach (self::DEMO_ACCOUNTS as $index => $account) {
            $profile = $staffProfiles[$index] ?? null;
            if (! $profile) {
                continue;
            }

            if (User::query()->where('email', $account['email'])->exists()) {
                continue;
            }

            $profile->update(['name' => $account['name']]);

            StaffAccountProvisioner::createForStaff($profile, $owner, [
                'email' => $account['email'],
                'password' => DemoSeederConstants::PASSWORD,
                'phone' => $account['phone'],
                'name' => $account['name'],
            ]);
        }

        $this->command?->info('Staff accounts seeded: owner@gmail.com + staff1/2/3@gmail.com (password 123456).');
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

        $this->command?->info('Inserted missing staff role (id='.Role::ID_STAFF.').');
    }
}
