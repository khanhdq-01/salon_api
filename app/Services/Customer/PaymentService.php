<?php

namespace App\Services\Customer;

use App\Contracts\Services\Customer\PaymentServiceInterface;
use App\Models\User;
use App\Services\Shared\ThrowsNotImplemented;

class PaymentService implements PaymentServiceInterface
{
    use ThrowsNotImplemented;

    public function initiatePayment(string $bookingId, array $data, User $actor): mixed
    {
        $this->notImplemented(self::class, __FUNCTION__);
    }

    public function getPaymentByBooking(string $bookingId, User $actor): mixed
    {
        $this->notImplemented(self::class, __FUNCTION__);
    }

    public function refundPayment(string $bookingId, User $actor): mixed
    {
        $this->notImplemented(self::class, __FUNCTION__);
    }

    public function handleWebhook(string $provider, array $payload): mixed
    {
        $this->notImplemented(self::class, __FUNCTION__);
    }
}
