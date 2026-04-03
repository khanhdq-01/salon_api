<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Contracts\Services\Customer\BookingServiceInterface;
use App\Exceptions\BusinessException;
use App\Http\Controllers\Concerns\PaginatesApiResource;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Customer\ListBookingRequest;
use App\Http\Requests\Api\V1\Customer\StoreBookingRequest;
use App\Http\Requests\Shared\RouteIdRequest;
use App\Http\Resources\Api\V1\Customer\BookingResource;
use App\Models\Booking;
use Illuminate\Http\JsonResponse;

class BookingController extends Controller
{
    use PaginatesApiResource;

    public function __construct(
        protected BookingServiceInterface $bookingService
    ) {}

    public function index(ListBookingRequest $request): JsonResponse
    {
        $this->authorize('viewAny', Booking::class);

        $paginator = $this->bookingService->listBookings($request->validated(), $request->user());

        return $this->paginatedResource($paginator, BookingResource::class, 'Lấy danh sách booking thành công');
    }

    public function store(StoreBookingRequest $request): JsonResponse
    {
        $this->authorize('create', Booking::class);

        try {
            $booking = $this->bookingService->createBooking($request->validated(), $request->user());
        } catch (BusinessException $e) {
            return $this->error($e->getMessage(), $e->getCode(), ['code' => $e->getErrorCode()]);
        }

        return $this->created(new BookingResource($booking), 'Tạo booking thành công');
    }

    public function show(RouteIdRequest $request, string $id): JsonResponse
    {
        try {
            $booking = $this->bookingService->getBookingById($id, auth()->user());
        } catch (BusinessException $e) {
            return $this->error($e->getMessage(), $e->getCode(), ['code' => $e->getErrorCode()]);
        }

        $this->authorize('view', $booking);

        return $this->success(new BookingResource($booking), 'Lấy chi tiết booking thành công');
    }

    public function destroy(RouteIdRequest $request, string $id): JsonResponse
    {
        try {
            $booking = $this->bookingService->getBookingById($id, auth()->user());
        } catch (BusinessException $e) {
            return $this->error($e->getMessage(), $e->getCode(), ['code' => $e->getErrorCode()]);
        }

        $this->authorize('delete', $booking);

        try {
            $this->bookingService->deleteBooking($id, auth()->user());
        } catch (BusinessException $e) {
            return $this->error($e->getMessage(), $e->getCode(), ['code' => $e->getErrorCode()]);
        }

        return $this->noContent('Xóa booking thành công');
    }
}
