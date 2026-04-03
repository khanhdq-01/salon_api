<?php

namespace App\Http\Controllers\Api\V1\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Owner\UploadSubscriptionPaymentProofRequest;
use App\Support\ImageUploadStorage;
use Illuminate\Http\JsonResponse;

class SubscriptionPaymentProofController extends Controller
{
    public function store(UploadSubscriptionPaymentProofRequest $request): JsonResponse
    {
        $stored = ImageUploadStorage::store(
            $request->file('image'),
            ImageUploadStorage::DIR_SUBSCRIPTION_PAYMENTS
        );

        return response()->json([
            'success' => true,
            'message' => 'Tải ảnh chứng từ thanh toán thành công',
            'data' => $stored,
        ]);
    }
}
