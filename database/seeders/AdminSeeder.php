<?php

namespace Database\Seeders;

use App\Models\Role;
use Database\Seeders\Concerns\SeedsIdempotentUsers;
use Database\Seeders\Support\DemoSeederConstants;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    use SeedsIdempotentUsers;

    public function run(): void
    {
        $this->seedUser([
            'role_id' => Role::ID_ADMIN,
            'name' => 'Quản Trị Viên',
            'email' => DemoSeederConstants::ADMIN_EMAIL,
            'phone' => '0903000001',
            'last_login_days_ago' => 0,
        ]);
    }
}
