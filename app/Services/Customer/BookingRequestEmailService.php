<?php

namespace App\Services\Customer;

use App\Contracts\Services\Admin\AdminSettingsServiceInterface;
use App\Mail\SubscriptionExpiryNotificationMail;
use App\Models\Booking;
use App\Models\EmailTemplate;
use App\Repositories\Interfaces\Customer\EmailTemplateRepositoryInterface;
use App\Support\QueuedMailer;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class BookingRequestEmailService
{
    public function __construct(
        protected AdminSettingsServiceInterface $settingsService,
        protected EmailTemplateRepositoryInterface $emailTemplateRepository,
    ) {}

    public function sendBookingRequestEmail(Booking $booking): bool
    {
        $settings = $this->settingsService->getSettings();

        if (! ($settings['enable_notifications'] ?? true)) {
            return false;
        }

        $booking->loadMissing([
            'salon:id,name,address,owner_id,phone',
            'salon.owner:id,name,email',
            'customer:id,name,email,phone',
            'staff:id,name',
            'bookingServices.service:id,name',
            'bookingServices.styleOption:id,name',
        ]);

        $recipientEmail = $booking->salon?->owner?->email;

        if (! $recipientEmail) {
            return false;
        }

        $template = $this->emailTemplateRepository->findActiveByKey(EmailTemplate::KEY_BOOKING_REQUEST);

        if (! $template) {
            return false;
        }

        try {
            $this->dispatchEmail($booking, $template, $recipientEmail, $settings);

            return true;
        } catch (\Throwable $exception) {
            Log::warning('Failed to send booking request email', [
                'booking_id' => $booking->id,
                'salon_id' => $booking->salon_id,
                'recipient' => $recipientEmail,
                'error' => $exception->getMessage(),
            ]);

            return false;
        }
    }

    protected function dispatchEmail(
        Booking $booking,
        EmailTemplate $template,
        string $recipientEmail,
        array $settings
    ): void {
        $salonName = $booking->salon?->name ?? '—';
        $ownerName = $booking->salon?->owner?->name ?? 'Quý khách';
        $customerName = $booking->customer?->name ?? '—';
        $customerEmail = $booking->customer?->email ?? '—';
        $customerPhone = $booking->customer?->phone ?? '—';
        $staffName = $booking->staff?->name ?? '—';
        $bookingDate = $booking->booking_date
            ? Carbon::parse($booking->booking_date)->format('d/m/Y')
            : '—';
        $bookingTime = $booking->booking_time
            ? substr((string) $booking->booking_time, 0, 5)
            : '—';
        $totalPrice = number_format((int) ($booking->total_price ?? 0), 0, ',', '.').' VNĐ';
        $duration = (int) ($booking->total_duration_minutes ?? 0);
        $servicesHtml = $this->buildServicesHtml($booking);
        $systemName = $settings['system_name'] ?? config('app.name', 'Salonify SaaS');

        $headerHtml = sprintf(
            '<p style="margin: 0 0 12px;">Xin chào <strong>%s</strong>,</p>'.
            '<p style="margin: 0 0 12px;">Salon <strong>%s</strong> vừa nhận yêu cầu đặt lịch mới từ khách hàng.</p>'.
            '<p style="margin: 0 0 4px;"><strong>Khách hàng:</strong> %s</p>'.
            '<p style="margin: 0 0 4px;"><strong>Email khách:</strong> %s</p>'.
            '<p style="margin: 0 0 4px;"><strong>SĐT khách:</strong> %s</p>'.
            '<p style="margin: 0 0 4px;"><strong>Ngày đặt:</strong> %s</p>'.
            '<p style="margin: 0 0 4px;"><strong>Giờ đặt:</strong> %s</p>'.
            '<p style="margin: 0 0 4px;"><strong>Nhân viên:</strong> %s</p>'.
            '<p style="margin: 0 0 4px;"><strong>Dịch vụ:</strong><br>%s</p>'.
            '<p style="margin: 0 0 4px;"><strong>Tổng tiền:</strong> %s</p>'.
            '<p style="margin: 0 0 4px;"><strong>Thời lượng:</strong> %d phút</p>'.
            '<p style="margin: 0 0 4px;"><strong>Thanh toán:</strong> Thanh toán tại salon</p>',
            e($ownerName),
            e($salonName),
            e($customerName),
            e($customerEmail),
            e($customerPhone),
            e($bookingDate),
            e($bookingTime),
            e($staffName),
            $servicesHtml,
            e($totalPrice),
            $duration
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

    protected function buildServicesHtml(Booking $booking): string
    {
        $lines = $booking->bookingServices
            ->map(function ($line) {
                $name = $line->service?->name ?? '—';
                if ($line->styleOption?->name) {
                    $name .= ' ('.$line->styleOption->name.')';
                }

                return e($name);
            })
            ->filter()
            ->values();

        if ($lines->isEmpty()) {
            return '—';
        }

        return $lines->implode('<br>');
    }
}
