<?php

namespace App\Http\Controllers\Api\V1\Staff;

use App\Contracts\Services\Staff\StaffPortalServiceInterface;
use App\Http\Controllers\Concerns\HandlesServiceException;
use App\Http\Controllers\Concerns\PaginatesApiResource;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Staff\GetStaffCalendarRequest;
use App\Http\Requests\Api\V1\Staff\GetStaffReportRequest;
use App\Http\Requests\Api\V1\Staff\ListStaffWorkSchedulesRequest;
use App\Http\Requests\Api\V1\Staff\SubmitStaffScheduleRequest;
use App\Http\Requests\Api\V1\Staff\UpdateStaffProfileRequest;
use App\Http\Resources\Api\V1\Owner\StaffScheduleResource;
use App\Http\Resources\Api\V1\Staff\StaffCalendarBookingResource;
use App\Http\Requests\Api\V1\Staff\CompleteStaffBookingRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StaffPortalController extends Controller
{
    use HandlesServiceException, PaginatesApiResource;

    public function __construct(
        protected StaffPortalServiceInterface $staffPortalService
    ) {}

    public function profile(Request $request): JsonResponse
    {
        return $this->tryService(function () use ($request) {
            $data = $this->staffPortalService->getProfile($request->user());

            return $this->success($data, 'Lấy hồ sơ nhân viên thành công');
        });
    }

    public function updateProfile(UpdateStaffProfileRequest $request): JsonResponse
    {
        return $this->tryService(function () use ($request) {
            $user = $this->staffPortalService->updateProfile($request->user(), $request->validated());

            return $this->success([
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'role' => $user->role?->name,
            ], 'Cập nhật hồ sơ thành công');
        });
    }

    public function dashboard(GetStaffReportRequest $request): JsonResponse
    {
        return $this->tryService(function () use ($request) {
            return $this->success(
                $this->staffPortalService->getDashboard($request->user(), $request->validated()),
                'Lấy dashboard thành công'
            );
        });
    }

    public function schedules(Request $request): JsonResponse
    {
        return $this->tryService(function () use ($request) {
            return $this->success(
                $this->staffPortalService->listSchedules($request->user()),
                'Lấy lịch làm việc thành công'
            );
        });
    }

    public function workSchedules(ListStaffWorkSchedulesRequest $request): JsonResponse
    {
        return $this->tryService(function () use ($request) {
            $paginator = $this->staffPortalService->paginateWorkSchedules(
                $request->user(),
                $request->validated()
            );

            return $this->paginatedResource(
                $paginator,
                StaffScheduleResource::class,
                'Lấy danh sách lịch làm việc thành công'
            );
        });
    }

    public function submitSchedules(SubmitStaffScheduleRequest $request): JsonResponse
    {
        return $this->tryService(function () use ($request) {
            $schedules = $this->staffPortalService->submitSchedules(
                $request->user(),
                $request->validated('schedules', [])
            );

            return $this->success($schedules, 'Đăng ký lịch làm việc thành công. Chủ salon sẽ xem xét duyệt.');
        });
    }

    public function report(GetStaffReportRequest $request): JsonResponse
    {
        return $this->tryService(function () use ($request) {
            return $this->success(
                $this->staffPortalService->getReport($request->user(), $request->validated()),
                'Lấy báo cáo doanh thu thành công'
            );
        });
    }

    public function calendar(GetStaffCalendarRequest $request): JsonResponse
    {
        return $this->tryService(function () use ($request) {
            $filters = $request->validated();

            if (
                ! empty($filters['date'])
                && empty($filters['start_date'])
                && empty($filters['end_date'])
                && empty($filters['page'])
                && empty($filters['per_page'])
            ) {
                return $this->success(
                    $this->staffPortalService->getCalendarDay(
                        $request->user(),
                        $filters['date']
                    ),
                    'Lấy lịch làm việc thành công'
                );
            }

            $paginator = $this->staffPortalService->paginateCalendar(
                $request->user(),
                $filters
            );

            return $this->paginatedResource(
                $paginator,
                StaffCalendarBookingResource::class,
                'Lấy lịch làm việc thành công'
            );
        });
    }

    public function completeBooking(CompleteStaffBookingRequest $request, string $id): JsonResponse
    {
        return $this->tryService(function () use ($request, $id) {
            return $this->success(
                $this->staffPortalService->completeAssignedBooking($request->user(), $id),
                'Đã đánh dấu hoàn thành lịch hẹn'
            );
        });
    }
}
