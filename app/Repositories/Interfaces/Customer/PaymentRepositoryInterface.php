<?php

namespace App\Repositories\Interfaces\Customer;

interface PaymentRepositoryInterface
{
    public function findByBookingId(string $bookingId): ?\App\Models\Payment;
}
