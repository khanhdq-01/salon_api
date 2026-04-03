<?php

namespace App\Services\Owner;

use App\Contracts\Services\Admin\AdminSettingsServiceInterface;
use App\Jobs\SendSubscriptionExpiryReminderJob;
use App\Models\EmailNotificationLog;
use App\Models\EmailTemplate;
use App\Models\Subscription;
use App\Repositories\Interfaces\Owner\EmailTemplateRepositoryInterface;
use App\Repositories\Interfaces\Owner\SubscriptionRepositoryInterface;
use Carbon\Carbon;

class SubscriptionExpiryEmailService
{
    public function __construct(
        protected AdminSettingsServiceInterface $settingsService,
        protected EmailTemplateRepositoryInterface $emailTemplateRepository,
        protected SubscriptionRepositoryInterface $subscriptionRepository,
    ) {}

    public function sendReminders(): array
    {
        $settings = $this->settingsService->getSettings();

        if (! ($settings['enable_notifications'] ?? true)) {
            return ['sent' => 0, 'skipped' => 0, 'reason' => 'notifications_disabled'];
        }

        $sent = 0;
        $skipped = 0;
        $today = Carbon::today();

        $rules = [
            EmailTemplate::KEY_EXPIRY_7_DAYS => $today->copy()->addDays(7),
            EmailTemplate::KEY_EXPIRY_3_DAYS => $today->copy()->addDays(3),
            EmailTemplate::KEY_EXPIRED => $today,
        ];

        foreach ($rules as $templateKey => $targetDate) {
            $template = $this->emailTemplateRepository->findActiveByKey($templateKey);

            if (! $template) {
                continue;
            }

            $subscriptions = $this->subscriptionRepository->getActiveExpiringOnDate(
                $targetDate->toDateString(),
                [
                    'owner:id,name,email',
                    'package:id,name',
                    'owner.ownedSalons' => fn ($query) => $query->withTrashed()->select('id', 'owner_id', 'name'),
                ]
            );

            foreach ($subscriptions as $subscription) {
                if ($this->alreadySent($subscription->id, $templateKey)) {
                    $skipped++;
                    continue;
                }

                $owner = $subscription->owner;
                $recipientEmail = $owner?->email;

                if (! $recipientEmail) {
                    $skipped++;
                    continue;
                }

                $daysRemaining = match ($templateKey) {
                    EmailTemplate::KEY_EXPIRY_7_DAYS => 7,
                    EmailTemplate::KEY_EXPIRY_3_DAYS => 3,
                    default => 0,
                };

                $this->queueReminderEmail(
                    $subscription,
                    $template,
                    $recipientEmail,
                    (int) $daysRemaining,
                    $settings
                );

                $sent++;
            }
        }

        return ['sent' => $sent, 'skipped' => $skipped];
    }

    protected function alreadySent(string $subscriptionId, string $templateKey): bool
    {
        return EmailNotificationLog::query()
            ->where('subscription_id', $subscriptionId)
            ->where('template_key', $templateKey)
            ->exists();
    }

    protected function queueReminderEmail(
        Subscription $subscription,
        EmailTemplate $template,
        string $recipientEmail,
        int $daysRemaining,
        array $settings
    ): void {
        $ownerName = $subscription->owner?->name ?? 'Quý khách';
        $packageName = $subscription->package?->name ?? '—';
        $salonName = $subscription->owner?->ownedSalons
            ?->pluck('name')
            ->filter()
            ->first() ?? '—';
        $expiryDate = Carbon::parse($subscription->end_date)->format('d/m/Y');
        $systemName = $settings['system_name'] ?? config('app.name', 'Salonify SaaS');

        $headerHtml = $this->buildHeaderHtml(
            $ownerName,
            $packageName,
            $salonName,
            $daysRemaining,
            $expiryDate,
            $template->template_key
        );
        $footerHtml = $this->buildFooterHtml($systemName);
        $fromAddress = $settings['email_sender_address'] ?? config('mail.from.address');
        $fromName = $settings['email_sender_name'] ?? config('mail.from.name');

        SendSubscriptionExpiryReminderJob::dispatch(
            subscriptionId: $subscription->id,
            templateKey: $template->template_key,
            recipientEmail: $recipientEmail,
            subjectLine: $template->subject,
            headerHtml: $headerHtml,
            bodyHtml: $template->content ?? '',
            footerHtml: $footerHtml,
            fromAddress: $fromAddress,
            fromName: $fromName,
        );
    }

    protected function buildHeaderHtml(
        string $ownerName,
        string $packageName,
        string $salonName,
        int $daysRemaining,
        string $expiryDate,
        string $templateKey
    ): string {
        if ($templateKey === EmailTemplate::KEY_EXPIRED) {
            $intro = sprintf(
                '<p style="margin: 0 0 12px;">Xin chào <strong>%s</strong>,</p>'.
                '<p style="margin: 0 0 12px;">Gói dịch vụ <strong>%s</strong> của salon <strong>%s</strong> đã hết hạn.</p>'.
                '<p style="margin: 0 0 4px;"><strong>Ngày hết hạn:</strong><br>%s</p>',
                e($ownerName),
                e($packageName),
                e($salonName),
                e($expiryDate)
            );
        } else {
            $intro = sprintf(
                '<p style="margin: 0 0 12px;">Xin chào <strong>%s</strong>,</p>'.
                '<p style="margin: 0 0 12px;">Gói dịch vụ <strong>%s</strong> của salon <strong>%s</strong> sẽ hết hạn sau <strong>%d</strong> ngày.</p>'.
                '<p style="margin: 0 0 4px;"><strong>Ngày hết hạn:</strong><br>%s</p>',
                e($ownerName),
                e($packageName),
                e($salonName),
                $daysRemaining,
                e($expiryDate)
            );
        }

        return $intro.'<hr style="border: none; border-top: 1px solid #e5e7eb; margin: 16px 0;">';
    }

    protected function buildFooterHtml(string $systemName): string
    {
        return sprintf(
            '<p style="margin: 0 0 4px;">Trân trọng,</p><p style="margin: 0;"><strong>%s</strong></p>',
            e($systemName)
        );
    }
}
