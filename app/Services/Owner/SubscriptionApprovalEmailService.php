<?php

namespace App\Services\Owner;

use App\Contracts\Services\Admin\AdminSettingsServiceInterface;
use App\Mail\SubscriptionExpiryNotificationMail;
use App\Models\EmailTemplate;
use App\Models\Subscription;
use App\Repositories\Interfaces\Owner\EmailTemplateRepositoryInterface;
use App\Support\QueuedMailer;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SubscriptionApprovalEmailService
{
    public function __construct(
        protected AdminSettingsServiceInterface $settingsService,
        protected EmailTemplateRepositoryInterface $emailTemplateRepository,
    ) {}

    public function sendApprovalEmail(Subscription $subscription): bool
    {
        $settings = $this->settingsService->getSettings();

        if (! ($settings['enable_notifications'] ?? true)) {
            return false;
        }

        $subscription->loadMissing([
            'owner:id,name,email',
            'package:id,name',
            'owner.ownedSalons' => fn ($query) => $query->withTrashed()->select('id', 'owner_id', 'name'),
        ]);

        $recipientEmail = $subscription->owner?->email;

        if (! $recipientEmail) {
            return false;
        }

        $template = $this->emailTemplateRepository->findActiveByKey(EmailTemplate::KEY_SUBSCRIPTION_APPROVED);

        if (! $template) {
            return false;
        }

        try {
            $this->dispatchEmail($subscription, $template, $recipientEmail, $settings);

            return true;
        } catch (\Throwable $exception) {
            Log::warning('Failed to send subscription approval email', [
                'subscription_id' => $subscription->id,
                'recipient' => $recipientEmail,
                'error' => $exception->getMessage(),
            ]);

            return false;
        }
    }

    protected function dispatchEmail(
        Subscription $subscription,
        EmailTemplate $template,
        string $recipientEmail,
        array $settings
    ): void {
        $ownerName = $subscription->owner?->name ?? 'Quý khách';
        $packageName = $subscription->package?->name ?? '—';
        $salonName = $subscription->owner?->ownedSalons
            ?->pluck('name')
            ->filter()
            ->first() ?? '—';
        $startDate = $subscription->start_date
            ? Carbon::parse($subscription->start_date)->format('d/m/Y')
            : '—';
        $endDate = $subscription->end_date
            ? Carbon::parse($subscription->end_date)->format('d/m/Y')
            : '—';
        $systemName = $settings['system_name'] ?? config('app.name', 'Salonify SaaS');

        $headerHtml = sprintf(
            '<p style="margin: 0 0 12px;">Xin chào <strong>%s</strong>,</p>'.
            '<p style="margin: 0 0 12px;">Gói dịch vụ <strong>%s</strong> của salon <strong>%s</strong> đã được admin duyệt thành công.</p>'.
            '<p style="margin: 0 0 4px;"><strong>Ngày bắt đầu:</strong> %s</p>'.
            '<p style="margin: 0 0 4px;"><strong>Ngày hết hạn:</strong> %s</p>',
            e($ownerName),
            e($packageName),
            e($salonName),
            e($startDate),
            e($endDate)
        ).'<hr style="border: none; border-top: 1px solid #e5e7eb; margin: 16px 0;">';

        $footerHtml = sprintf(
            '<p style="margin: 0 0 4px;">Trân trọng,</p><p style="margin: 0;"><strong>%s</strong></p>',
            e($systemName)
        );

        $fromAddress = $settings['email_sender_address'] ?? config('mail.from.address');
        $fromName = $settings['email_sender_name'] ?? config('mail.from.name');

        $mail = new SubscriptionExpiryNotificationMail(
            subjectLine: $template->subject,
            headerHtml: $headerHtml,
            bodyHtml: $template->content ?? '',
            footerHtml: $footerHtml,
            fromAddress: $fromAddress,
            fromName: $fromName,
        );

        QueuedMailer::to($recipientEmail, $mail);
    }
}
