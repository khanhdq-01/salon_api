<?php

namespace App\Jobs;

use App\Mail\SubscriptionExpiryNotificationMail;
use App\Models\EmailNotificationLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class SendSubscriptionExpiryReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(
        public string $subscriptionId,
        public string $templateKey,
        public string $recipientEmail,
        public string $subjectLine,
        public string $headerHtml,
        public string $bodyHtml,
        public string $footerHtml,
        public string $fromAddress,
        public string $fromName,
    ) {}

    public function handle(): void
    {
        if ($this->alreadySent()) {
            return;
        }

        $mail = new SubscriptionExpiryNotificationMail(
            subjectLine: $this->subjectLine,
            headerHtml: $this->headerHtml,
            bodyHtml: $this->bodyHtml,
            footerHtml: $this->footerHtml,
            fromAddress: $this->fromAddress,
            fromName: $this->fromName,
        );

        Mail::to($this->recipientEmail)->send($mail);

        EmailNotificationLog::query()->create([
            'id' => (string) Str::uuid(),
            'subscription_id' => $this->subscriptionId,
            'template_key' => $this->templateKey,
            'recipient_email' => $this->recipientEmail,
            'sent_at' => now(),
            'created_at' => now(),
        ]);
    }

    protected function alreadySent(): bool
    {
        return EmailNotificationLog::query()
            ->where('subscription_id', $this->subscriptionId)
            ->where('template_key', $this->templateKey)
            ->exists();
    }
}
