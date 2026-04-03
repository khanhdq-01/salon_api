<?php

namespace App\Jobs;

use App\Mail\AuthTransactionalMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SendVerifyEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(
        public string $recipientEmail,
        public string $subjectLine,
        public string $headline,
        public string $introHtml,
        public string $footerHtml,
        public string $fromAddress,
        public string $fromName,
        public ?string $logoUrl,
        public string $systemName,
        public string $ctaUrl,
        public ?string $bodyHtml = null,
        public ?string $expiresNotice = null,
    ) {}

    public function handle(): void
    {
        $mail = new AuthTransactionalMail(
            subjectLine: $this->subjectLine,
            headline: $this->headline,
            introHtml: $this->introHtml,
            footerHtml: $this->footerHtml,
            fromAddress: $this->fromAddress,
            fromName: $this->fromName,
            logoUrl: $this->logoUrl,
            systemName: $this->systemName,
            ctaLabel: 'Xác thực Email',
            ctaUrl: $this->ctaUrl,
            bodyHtml: $this->bodyHtml,
            expiresNotice: $this->expiresNotice,
        );

        Mail::to($this->recipientEmail)->send($mail);
    }

    public function failed(Throwable $exception): void
    {
        Log::error('SendVerifyEmailJob failed', [
            'email' => $this->recipientEmail,
            'error' => $exception->getMessage(),
        ]);
    }
}
