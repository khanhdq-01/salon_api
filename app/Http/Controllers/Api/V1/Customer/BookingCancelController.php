<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Contracts\Services\Customer\BookingServiceInterface;
use App\Exceptions\BusinessException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Customer\CancelBookingRequest;
use App\Http\Resources\Api\V1\Customer\BookingResource;
use Illuminate\Http\JsonResponse;

class BookingCancelController extends Controller
{
    public function __construct(
        protected BookingServiceInterface $bookingService
    ) {}

    public function __invoke(CancelBookingRequest $request, string $id): JsonResponse
    {
        try {
            $booking = $this->bookingService->getBookingById($id, $request->user());
        } catch (BusinessException $e) {
            return $this->error($e->getMessage(), $e->getCode(), ['code' => $e->getErrorCode()]);
        }

        $this->authorize('cancel', $booking);

        try {
            $updated = $this->bookingService->cancelBooking($id, $request->validated(), $request->user());
        } catch (BusinessException $e) {
            return $this->error($e->getMessage(), $e->getCode(), ['code' => $e->getErrorCode()]);
        }

        return $this->success(new BookingResource($updated), 'Hủy booking thành công');
    }
}
