<?php

namespace App\Console\Commands;

use App\Services\Customer\NotificationService;
use Illuminate\Console\Command;

class DispatchScheduledNotificationsCommand extends Command
{
    protected $signature = 'notifications:dispatch-scheduled';

    protected $description = 'Dispatch owner notifications that have reached their scheduled send time';

    public function handle(NotificationService $notificationService): int
    {
        $processed = $notificationService->processDueScheduledNotifications();

        $this->info("Scheduled notifications dispatched: {$processed}");

        return self::SUCCESS;
    }
}
