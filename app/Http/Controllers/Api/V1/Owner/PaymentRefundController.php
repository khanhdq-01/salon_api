<?php

namespace App\Http\Controllers\Api\V1\Owner;

use App\Contracts\Services\Customer\PaymentServiceInterface;
use App\Http\Controllers\Concerns\HandlesServiceException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Owner\RefundPaymentRequest;
use Illuminate\Http\JsonResponse;

class PaymentRefundController extends Controller
{
    use HandlesServiceException;

    public function __construct(
        protected PaymentServiceInterface $paymentService
    ) {}

    public function __invoke(RefundPaymentRequest $request, string $bookingId): JsonResponse
    {
        return $this->tryService(fn () => $this->paymentService->refundPayment($bookingId, $request->user()));
    }
}
