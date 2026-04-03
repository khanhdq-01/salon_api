<?php

use App\Models\Role;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $exists = DB::table('roles')->where('id', Role::ID_STAFF)->exists();

        if ($exists) {
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

    public function down(): void
    {
        DB::table('users')->where('role_id', Role::ID_STAFF)->delete();

        DB::table('roles')->where('id', Role::ID_STAFF)->delete();
    }
};
