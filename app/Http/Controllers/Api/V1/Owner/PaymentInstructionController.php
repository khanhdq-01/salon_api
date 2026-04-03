<?php

namespace App\Http\Controllers\Api\V1\Owner;

use App\Contracts\Services\Owner\OwnerPaymentInstructionServiceInterface;
use App\Http\Controllers\Concerns\HandlesServiceException;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\Owner\OwnerPaymentInstructionResource;
use Illuminate\Http\JsonResponse;

class PaymentInstructionController extends Controller
{
    use HandlesServiceException;

    public function __construct(
        protected OwnerPaymentInstructionServiceInterface $paymentInstructionService
    ) {}

    public function show(): JsonResponse
    {
        return $this->tryService(function () {
            $instruction = $this->paymentInstructionService->getActiveInstruction();

            if (! $instruction) {
                return response()->json([
                    'success' => true,
                    'message' => 'Chưa có hướng dẫn thanh toán.',
                    'data' => null,
                ]);
            }

            return new OwnerPaymentInstructionResource($instruction);
        }, 'Lấy hướng dẫn thanh toán thành công');
    }
}
