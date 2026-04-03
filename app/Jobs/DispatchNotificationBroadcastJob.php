<?php

namespace App\Jobs;

use App\Services\Customer\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DispatchNotificationBroadcastJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(
        public string $broadcastId,
    ) {}

    public function handle(NotificationService $notificationService): void
    {
        $notificationService->dispatchBroadcastById($this->broadcastId);
    }
}
