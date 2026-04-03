<?php

use App\Models\Role;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $roles = [
            [
                'id' => Role::ID_ADMIN,
                'name' => Role::ADMIN,
                'display_name' => 'Quản trị viên',
            ],
            [
                'id' => Role::ID_OWNER,
                'name' => Role::OWNER,
                'display_name' => 'Chủ salon',
            ],
            [
                'id' => Role::ID_STAFF,
                'name' => Role::STAFF,
                'display_name' => 'Nhân viên',
            ],
            [
                'id' => Role::ID_CUSTOMER,
                'name' => Role::CUSTOMER,
                'display_name' => 'Khách hàng',
            ],
        ];

        foreach ($roles as $role) {
            DB::table('roles')->updateOrInsert(
                ['id' => $role['id']],
                [
                    'name' => $role['name'],
                    'display_name' => $role['display_name'],
                    'description' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            );
        }
    }

    public function down(): void
    {
        // Roles are referenced by users — do not delete on rollback.
    }
};
