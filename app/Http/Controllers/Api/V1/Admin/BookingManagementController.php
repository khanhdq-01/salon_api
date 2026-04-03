<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Contracts\Services\Admin\AdminBookingManagementServiceInterface;
use App\Http\Controllers\Concerns\HandlesServiceException;
use App\Http\Controllers\Concerns\PaginatesApiResource;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Admin\AdminBookingStatusRequest;
use App\Http\Requests\Api\V1\Admin\ListAdminBookingsRequest;
use App\Http\Requests\Shared\RouteIdRequest;
use App\Http\Resources\Api\V1\Customer\BookingResource;
use Illuminate\Http\JsonResponse;

class BookingManagementController extends Controller
{
    use HandlesServiceException, PaginatesApiResource;

    public function __construct(
        protected AdminBookingManagementServiceInterface $bookingManagementService
    ) {}

    public function index(ListAdminBookingsRequest $request): JsonResponse
    {
        $paginator = $this->bookingManagementService->listBookings($request->validated());

        return $this->paginatedResource($paginator, BookingResource::class, 'Lấy danh sách booking thành công');
    }

    public function show(RouteIdRequest $request, string $id): JsonResponse
    {
        return $this->tryService(
            fn () => new BookingResource($this->bookingManagementService->getBooking($id)),
            'Lấy chi tiết booking thành công'
        );
    }

    public function confirm(RouteIdRequest $request, string $id): JsonResponse
    {
        return $this->tryService(
            fn () => new BookingResource($this->bookingManagementService->confirmBooking($id)),
            'Xác nhận booking thành công'
        );
    }

    public function complete(RouteIdRequest $request, string $id): JsonResponse
    {
        return $this->tryService(
            fn () => new BookingResource($this->bookingManagementService->completeBooking($id)),
            'Hoàn thành booking thành công'
        );
    }

    public function cancel(AdminBookingStatusRequest $request, string $id): JsonResponse
    {
        return $this->tryService(
            fn () => new BookingResource(
                $this->bookingManagementService->cancelBooking($id, $request->validated('reason'))
            ),
            'Hủy booking thành công'
        );
    }

    public function updateStatus(AdminBookingStatusRequest $request, string $id): JsonResponse
    {
        return $this->tryService(
            fn () => new BookingResource(
                $this->bookingManagementService->updateStatus($id, $request->validated('status'), $request->validated('reason'))
            ),
            'Cập nhật trạng thái booking thành công'
        );
    }

    public function destroy(RouteIdRequest $request, string $id): JsonResponse
    {
        return $this->tryService(
            fn () => $this->bookingManagementService->deleteBooking($id),
            'Xóa booking thành công'
        );
    }
}
