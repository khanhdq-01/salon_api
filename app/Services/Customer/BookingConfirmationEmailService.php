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

class BookingConfirmationEmailService
{
    public function __construct(
        protected AdminSettingsServiceInterface $settingsService,
        protected EmailTemplateRepositoryInterface $emailTemplateRepository,
    ) {}

    public function sendConfirmationEmail(Booking $booking): bool
    {
        $settings = $this->settingsService->getSettings();

        if (! ($settings['enable_notifications'] ?? true)) {
            return false;
        }

        $booking->loadMissing([
            'salon:id,name,address,owner_id,phone',
            'customer:id,name,email,phone',
            'staff:id,name',
            'bookingServices.service:id,name',
            'bookingServices.styleOption:id,name,extra_price',
        ]);

        $recipientEmail = $booking->customer?->email;

        if (! $recipientEmail) {
            return false;
        }

        $template = $this->emailTemplateRepository->findActiveByKey(EmailTemplate::KEY_BOOKING_CONFIRMED);

        if (! $template) {
            return false;
        }

        try {
            $this->dispatchEmail($booking, $template, $recipientEmail, $settings);

            return true;
        } catch (\Throwable $exception) {
            Log::warning('Failed to send booking confirmation email', [
                'booking_id' => $booking->id,
                'customer_id' => $booking->customer_id,
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
        $customerName = $booking->customer?->name ?? 'Quý khách';
        $salonName = $booking->salon?->name ?? '—';
        $salonAddress = $booking->salon?->address ?? '—';
        $salonPhone = $booking->salon?->phone ?? '—';
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
            '<p style="margin: 0 0 12px;">Lịch hẹn của bạn tại salon <strong>%s</strong> đã được <strong>xác nhận thành công</strong>.</p>'.
            '<p style="margin: 0 0 4px;"><strong>Salon:</strong> %s</p>'.
            '<p style="margin: 0 0 4px;"><strong>Địa chỉ:</strong> %s</p>'.
            '<p style="margin: 0 0 4px;"><strong>SĐT salon:</strong> %s</p>'.
            '<p style="margin: 0 0 4px;"><strong>Ngày hẹn:</strong> %s</p>'.
            '<p style="margin: 0 0 4px;"><strong>Giờ hẹn:</strong> %s</p>'.
            '<p style="margin: 0 0 4px;"><strong>Nhân viên:</strong> %s</p>'.
            '<p style="margin: 0 0 4px;"><strong>Dịch vụ:</strong><br>%s</p>'.
            '<p style="margin: 0 0 4px;"><strong>Tổng tiền:</strong> %s</p>'.
            '<p style="margin: 0 0 4px;"><strong>Thời lượng:</strong> %d phút</p>'.
            '<p style="margin: 0 0 4px;"><strong>Thanh toán:</strong> Thanh toán tại salon</p>'.
            '<p style="margin: 0 0 4px;"><strong>Trạng thái:</strong> Đã xác nhận</p>',
            e($customerName),
            e($salonName),
            e($salonName),
            e($salonAddress),
            e($salonPhone),
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
                $price = number_format((int) ($line->price ?? 0), 0, ',', '.').' VNĐ';
                $name .= ' — '.$price;

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
