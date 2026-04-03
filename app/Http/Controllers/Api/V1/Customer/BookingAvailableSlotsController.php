<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Contracts\Services\Customer\BookingServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Customer\AvailableSlotsRequest;
use Illuminate\Http\JsonResponse;

class BookingAvailableSlotsController extends Controller
{
    public function __construct(
        protected BookingServiceInterface $bookingService
    ) {}

    public function bySalon(AvailableSlotsRequest $request, string $salonId): JsonResponse
    {
        try {
            $data = $this->bookingService->getAvailableSlots($salonId, $request->validated());
        } catch (\App\Exceptions\BusinessException $e) {
            return $this->error($e->getMessage(), $e->getCode(), ['code' => $e->getErrorCode()]);
        }

        return $this->success($data, 'Lấy slot trống thành công');
    }

    public function index(AvailableSlotsRequest $request): JsonResponse
    {
        $salonId = $request->validated('salon_id');

        try {
            $data = $this->bookingService->getAvailableSlots($salonId, $request->validated());
        } catch (\App\Exceptions\BusinessException $e) {
            return $this->error($e->getMessage(), $e->getCode(), ['code' => $e->getErrorCode()]);
        }

        return $this->success($data, 'Lấy slot trống thành công');
    }
}
