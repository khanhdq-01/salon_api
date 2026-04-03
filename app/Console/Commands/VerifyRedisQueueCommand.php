<?php

namespace App\Console\Commands;

use App\Jobs\VerifyRedisQueueProbeJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

class VerifyRedisQueueCommand extends Command
{
    protected $signature = 'queue:verify-redis';

    protected $description = 'Verify Redis queue connectivity (Phase 3)';

    public function handle(): int
    {
        $connection = config('queue.default');
        $this->info("Queue connection: {$connection}");

        if ($connection !== 'redis') {
            $this->warn('QUEUE_CONNECTION is not redis. Set QUEUE_CONNECTION=redis in .env');

            return self::FAILURE;
        }

        $probeValue = 'ok-'.now()->timestamp;
        Cache::forget('health:redis-queue-probe');

        VerifyRedisQueueProbeJob::dispatch($probeValue);

        $exitCode = Artisan::call('queue:work', [
            'connection' => 'redis',
            '--once' => true,
            '--stop-when-empty' => true,
        ]);

        if ($exitCode !== 0) {
            $this->error('queue:work failed to process probe job.');

            return self::FAILURE;
        }

        $readBack = Cache::get('health:redis-queue-probe');
        Cache::forget('health:redis-queue-probe');

        if ($readBack !== $probeValue) {
            $this->error('Queue probe job did not write expected cache value.');

            return self::FAILURE;
        }

        $this->info('Redis queue dispatch/process: OK');
        $this->line('  [info] Run a worker in dev: php artisan queue:work redis --tries=3');

        return self::SUCCESS;
    }
}
