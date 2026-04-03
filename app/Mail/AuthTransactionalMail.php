<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AuthTransactionalMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $subjectLine,
        public string $headline,
        public string $introHtml,
        public string $footerHtml,
        public string $fromAddress,
        public string $fromName,
        public ?string $logoUrl = null,
        public ?string $systemName = null,
        public ?string $ctaLabel = null,
        public ?string $ctaUrl = null,
        public ?string $bodyHtml = null,
        public ?string $expiresNotice = null,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address($this->fromAddress, $this->fromName),
            subject: $this->subjectLine,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.auth-transactional',
            with: [
                'subjectLine' => $this->subjectLine,
                'headline' => $this->headline,
                'introHtml' => $this->introHtml,
                'footerHtml' => $this->footerHtml,
                'logoUrl' => $this->logoUrl,
                'systemName' => $this->systemName,
                'ctaLabel' => $this->ctaLabel,
                'ctaUrl' => $this->ctaUrl,
                'bodyHtml' => $this->bodyHtml,
                'expiresNotice' => $this->expiresNotice,
            ],
        );
    }
}
