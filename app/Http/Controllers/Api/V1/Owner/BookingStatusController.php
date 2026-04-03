<?php

namespace App\Http\Controllers\Api\V1\Owner;

use App\Contracts\Services\Customer\BookingServiceInterface;
use App\Exceptions\BusinessException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Customer\UpdateBookingStatusRequest;
use App\Http\Resources\Api\V1\Customer\BookingResource;
use Illuminate\Http\JsonResponse;

class BookingStatusController extends Controller
{
    public function __construct(
        protected BookingServiceInterface $bookingService
    ) {}

    public function confirm(string $id): JsonResponse
    {
        try {
            $booking = $this->bookingService->getBookingById($id, auth()->user());
        } catch (BusinessException $e) {
            return $this->error($e->getMessage(), $e->getCode(), ['code' => $e->getErrorCode()]);
        }

        $this->authorize('manage', $booking);

        try {
            $updated = $this->bookingService->confirmBooking($id, auth()->user());
        } catch (BusinessException $e) {
            return $this->error($e->getMessage(), $e->getCode(), ['code' => $e->getErrorCode()]);
        }

        return $this->success(new BookingResource($updated), 'Xác nhận booking thành công');
    }

    public function complete(string $id): JsonResponse
    {
        try {
            $booking = $this->bookingService->getBookingById($id, auth()->user());
        } catch (BusinessException $e) {
            return $this->error($e->getMessage(), $e->getCode(), ['code' => $e->getErrorCode()]);
        }

        $this->authorize('manage', $booking);

        try {
            $updated = $this->bookingService->completeBooking($id, auth()->user());
        } catch (BusinessException $e) {
            return $this->error($e->getMessage(), $e->getCode(), ['code' => $e->getErrorCode()]);
        }

        return $this->success(new BookingResource($updated), 'Hoàn thành booking thành công');
    }

    public function update(UpdateBookingStatusRequest $request, string $id): JsonResponse
    {
        try {
            $booking = $this->bookingService->getBookingById($id, auth()->user());
        } catch (BusinessException $e) {
            return $this->error($e->getMessage(), $e->getCode(), ['code' => $e->getErrorCode()]);
        }

        $this->authorize('manage', $booking);

        try {
            $updated = $this->bookingService->updateBookingStatus(
                $id,
                $request->validated('status'),
                $request->user()
            );
        } catch (BusinessException $e) {
            return $this->error($e->getMessage(), $e->getCode(), ['code' => $e->getErrorCode()]);
        }

        return $this->success(new BookingResource($updated), 'Cập nhật trạng thái booking thành công');
    }
}
