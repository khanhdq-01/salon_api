<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'pgsql') {
            DB::statement("
                CREATE UNIQUE INDEX uq_bookings_active_slot
                ON bookings (salon_id, seat_id, booking_date, booking_time)
                WHERE status NOT IN ('cancelled') AND deleted_at IS NULL
            ");
        } else {
            // MySQL: composite index hỗ trợ query slot; unique partial cần app-layer lock
            DB::statement('
                CREATE INDEX idx_bookings_active_slot
                ON bookings (salon_id, seat_id, booking_date, booking_time, status)
            ');
        }
    }

    public function down(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'pgsql') {
            DB::statement('DROP INDEX IF EXISTS uq_bookings_active_slot');
        } else {
            DB::statement('DROP INDEX idx_bookings_active_slot ON bookings');
        }
    }
};
