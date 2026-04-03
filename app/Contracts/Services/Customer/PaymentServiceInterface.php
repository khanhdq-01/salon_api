<?php

namespace App\Contracts\Services\Customer;

interface PaymentServiceInterface
{
    public function initiatePayment(string $bookingId, array $data, \App\Models\User $actor): mixed;

    public function getPaymentByBooking(string $bookingId, \App\Models\User $actor): mixed;

    public function refundPayment(string $bookingId, \App\Models\User $actor): mixed;

    public function handleWebhook(string $provider, array $payload): mixed;
}
