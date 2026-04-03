<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('subscriptions:send-expiry-reminders')
            ->dailyAt('08:00')
            ->timezone('Asia/Ho_Chi_Minh');

        $schedule->command('notifications:dispatch-scheduled')
            ->everyMinute()
            ->timezone('Asia/Ho_Chi_Minh');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
