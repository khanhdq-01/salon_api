<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Contracts\Services\Customer\PaymentServiceInterface;
use App\Http\Controllers\Concerns\HandlesServiceException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Customer\InitiatePaymentRequest;
use App\Http\Requests\Shared\RouteBookingIdRequest;
use Illuminate\Http\JsonResponse;

class PaymentController extends Controller
{
    use HandlesServiceException;

    public function __construct(
        protected PaymentServiceInterface $paymentService
    ) {}

    public function store(InitiatePaymentRequest $request, string $bookingId): JsonResponse
    {
        return $this->tryService(fn () => $this->paymentService->initiatePayment($bookingId, $request->validated(), $request->user()));
    }

    public function show(RouteBookingIdRequest $request, string $bookingId): JsonResponse
    {
        return $this->tryService(fn () => $this->paymentService->getPaymentByBooking($bookingId, auth()->user()));
    }
}
