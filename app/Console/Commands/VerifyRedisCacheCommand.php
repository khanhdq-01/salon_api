<?php

namespace App\Console\Commands;

use App\Support\BookingSlotLock;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

class VerifyRedisCacheCommand extends Command
{
    protected $signature = 'cache:verify-redis';

    protected $description = 'Verify Redis connectivity and Laravel cache store (Phase 1–4)';

    public function handle(): int
    {
        $driver = config('cache.default');
        $this->info("Cache driver: {$driver}");

        if ($driver !== 'redis') {
            $this->warn('CACHE_DRIVER is not redis. Set CACHE_DRIVER=redis in .env');

            return self::FAILURE;
        }

        try {
            $pong = Redis::connection('cache')->ping();
            $this->info('Redis ping: '.(is_string($pong) ? $pong : 'OK'));
        } catch (\Throwable $e) {
            $this->error('Redis connection failed: '.$e->getMessage());
            $this->line('Ensure Redis is running (Laragon: Menu → Redis → Start).');

            return self::FAILURE;
        }

        $probeKey = 'health:redis-cache-probe';
        $probeValue = 'ok-'.now()->timestamp;

        Cache::put($probeKey, $probeValue, 60);
        $readBack = Cache::get($probeKey);
        Cache::forget($probeKey);

        if ($readBack !== $probeValue) {
            $this->error('Cache read/write mismatch via Redis store.');

            return self::FAILURE;
        }

        $this->info('Cache read/write: OK');

        $versionProbeSalon = '00000000-0000-0000-0000-000000000001';
        $versionProbeDate = now()->toDateString();
        $versionKey = "available-slots:version:{$versionProbeSalon}:{$versionProbeDate}";

        Cache::increment($versionKey);
        $version = (int) Cache::get($versionKey, 0);
        Cache::forget($versionKey);

        if ($version < 1) {
            $this->error('Available-slots version increment probe failed.');

            return self::FAILURE;
        }

        $this->info('Available-slots version key: OK');

        $lockSalonId = '00000000-0000-0000-0000-000000000002';
        $lockStaffId = '00000000-0000-0000-0000-000000000003';
        $lockDate = now()->toDateString();
        $lockKey = BookingSlotLock::staffKey($lockSalonId, $lockStaffId, $lockDate);
        $lock = Cache::lock($lockKey, BookingSlotLock::TTL_SECONDS);

        if (! $lock->get()) {
            $this->error('Booking slot lock acquire probe failed.');

            return self::FAILURE;
        }

        $lock->release();
        $this->info('Booking slot lock: OK');

        $knownKeys = [
            'system:settings' => 'System settings (SystemSettings)',
            'salons:list:version' => 'Salon list cache version (SalonService)',
        ];

        $this->newLine();
        $this->comment('Existing application cache keys (if warmed):');

        foreach ($knownKeys as $key => $label) {
            $exists = Cache::has($key);
            $this->line(sprintf('  [%s] %s — %s', $exists ? 'hit' : 'miss', $key, $label));
        }

        $todayPrefix = 'salons:available_today:'.now()->toDateString();
        $this->line("  [info] Today availability keys use prefix: {$todayPrefix}:v…:…");
        $this->line('  [info] Available slots keys use prefix: available-slots:{salonId}:{date}:v…:sv…:…');
        $this->line('  [info] Booking slot locks use prefix: booking-slot-lock:…');

        $this->newLine();
        $this->info('Redis cache Phase 1–4 is configured correctly.');

        return self::SUCCESS;
    }
}
