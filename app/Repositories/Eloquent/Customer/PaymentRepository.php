<?php

namespace App\Repositories\Eloquent\Customer;

use App\Repositories\Interfaces\Customer\PaymentRepositoryInterface;
use App\Models\Payment;
use App\Repositories\Eloquent\Concerns\ThrowsNotImplementedRepository;

class PaymentRepository implements PaymentRepositoryInterface
{
    use ThrowsNotImplementedRepository;

    public function findByBookingId(string $bookingId): ?Payment
    {
        $this->notImplemented(self::class, __FUNCTION__);
    }
}
