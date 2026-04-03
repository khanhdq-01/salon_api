<?php

namespace Database\Seeders;

use App\Models\Role;
use Database\Seeders\Concerns\SeedsIdempotentUsers;
use Database\Seeders\Data\DemoOwnersData;
use Illuminate\Database\Seeder;

class OwnerSeeder extends Seeder
{
    use SeedsIdempotentUsers;

    public function run(): void
    {
        foreach (DemoOwnersData::all() as $owner) {
            $this->seedUser([
                'role_id' => Role::ID_OWNER,
                'name' => $owner['name'],
                'email' => $owner['email'],
                'phone' => $owner['phone'],
                'address' => $owner['address'],
                'avatar_url' => $owner['avatar_url'],
                'last_login_days_ago' => $owner['last_login_days_ago'],
            ]);
        }
    }
}
