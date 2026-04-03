<?php

use App\Models\Salon;
use App\Models\SalonSetting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();

        Salon::query()
            ->select('id')
            ->orderBy('id')
            ->chunkById(100, function ($salons) use ($now) {
                $rows = [];

                foreach ($salons as $salon) {
                    $rows[] = array_merge(SalonSetting::defaultAttributes($salon->id), [
                        'id' => (string) \Illuminate\Support\Str::uuid(),
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }

                if ($rows !== []) {
                    DB::table('salon_settings')->insertOrIgnore($rows);
                }
            });
    }

    public function down(): void
    {
        // Keep seeded settings on rollback to avoid data loss.
    }
};
