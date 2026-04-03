<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Contracts\Services\Customer\BookingServiceInterface;
use App\Exceptions\BusinessException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Customer\RescheduleBookingRequest;
use App\Http\Resources\Api\V1\Customer\BookingResource;
use Illuminate\Http\JsonResponse;

class BookingRescheduleController extends Controller
{
    public function __construct(
        protected BookingServiceInterface $bookingService
    ) {}

    public function __invoke(RescheduleBookingRequest $request, string $id): JsonResponse
    {
        try {
            $booking = $this->bookingService->getBookingById($id, $request->user());
        } catch (BusinessException $e) {
            return $this->error($e->getMessage(), $e->getCode(), ['code' => $e->getErrorCode()]);
        }

        $this->authorize('reschedule', $booking);

        try {
            $updated = $this->bookingService->rescheduleBooking($id, $request->validated(), $request->user());
        } catch (BusinessException $e) {
            return $this->error($e->getMessage(), $e->getCode(), ['code' => $e->getErrorCode()]);
        }

        return $this->success(new BookingResource($updated), 'Đổi lịch booking thành công');
    }
}
