<?php

namespace App\Console\Commands;

use App\Services\Owner\SubscriptionExpiryEmailService;
use App\Support\SubscriptionExpiry;
use Illuminate\Console\Command;

class SendSubscriptionExpiryRemindersCommand extends Command
{
    protected $signature = 'subscriptions:send-expiry-reminders';

    protected $description = 'Send subscription expiry reminder emails (7 days, 3 days, expired)';

    public function handle(SubscriptionExpiryEmailService $emailService): int
    {
        SubscriptionExpiry::syncExpiredSubscriptions();

        $result = $emailService->sendReminders();

        if (($result['reason'] ?? null) === 'notifications_disabled') {
            $this->info('System notifications are disabled. No emails sent.');

            return self::SUCCESS;
        }

        $this->info(sprintf(
            'Subscription expiry emails processed. Sent: %d, Skipped: %d',
            $result['sent'],
            $result['skipped']
        ));

        return self::SUCCESS;
    }
}
