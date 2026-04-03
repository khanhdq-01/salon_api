<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Contracts\Services\Customer\PaymentServiceInterface;
use App\Http\Controllers\Concerns\HandlesServiceException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Customer\PaymentWebhookRequest;
use Illuminate\Http\JsonResponse;

class PaymentCallbackController extends Controller
{
    use HandlesServiceException;

    public function __construct(
        protected PaymentServiceInterface $paymentService
    ) {}

    public function __invoke(PaymentWebhookRequest $request, string $provider): JsonResponse
    {
        return $this->tryService(fn () => $this->paymentService->handleWebhook(
            $request->validated('provider'),
            $request->webhookPayload()
        ));
    }
}
