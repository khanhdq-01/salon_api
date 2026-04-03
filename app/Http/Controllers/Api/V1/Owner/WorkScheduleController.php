<?php

namespace App\Http\Controllers\Api\V1\Owner;

use App\Contracts\Services\Owner\OwnerWorkScheduleServiceInterface;
use App\Http\Controllers\Concerns\HandlesServiceException;
use App\Http\Controllers\Concerns\PaginatesApiResource;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Owner\ListWorkSchedulesRequest;
use App\Http\Requests\Api\V1\Owner\ReviewWorkScheduleRequest;
use App\Http\Requests\Api\V1\Owner\StoreWorkScheduleRequest;
use App\Http\Requests\Api\V1\Owner\UpdateWorkScheduleRequest;
use App\Http\Resources\Api\V1\Owner\StaffScheduleResource;
use App\Models\StaffSchedule;
use Illuminate\Http\JsonResponse;

class WorkScheduleController extends Controller
{
    use HandlesServiceException;
    use PaginatesApiResource;

    public function __construct(
        protected OwnerWorkScheduleServiceInterface $workScheduleService
    ) {}

    public function calendar(ListWorkSchedulesRequest $request): JsonResponse
    {
        $this->authorize('viewAny', StaffSchedule::class);

        return $this->tryService(function () use ($request) {
            $filters = $request->validated();

            if (! empty($filters['view']) || ! empty($filters['period'])) {
                $items = $this->workScheduleService->listCalendar(
                    $request->user(),
                    $filters
                );

                return $this->success(
                    StaffScheduleResource::collection($items),
                    'Lấy lịch làm việc thành công'
                );
            }

            $paginator = $this->workScheduleService->listApproved(
                $request->user(),
                $filters
            );

            return $this->paginatedResource(
                $paginator,
                StaffScheduleResource::class,
                'Lấy lịch làm việc thành công'
            );
        });
    }

    public function pending(ListWorkSchedulesRequest $request): JsonResponse
    {
        $this->authorize('viewAny', StaffSchedule::class);

        return $this->tryService(function () use ($request) {
            $paginator = $this->workScheduleService->listPending(
                $request->user(),
                $request->validated()
            );

            return $this->paginatedResource($paginator, StaffScheduleResource::class, 'Lấy yêu cầu chờ duyệt thành công');
        });
    }

    public function approveAll(ListWorkSchedulesRequest $request): JsonResponse
    {
        $this->authorize('viewAny', StaffSchedule::class);

        return $this->tryService(function () use ($request) {
            $result = $this->workScheduleService->approveAll(
                $request->user(),
                $request->validated()
            );

            $message = $result['approved_count'] > 0
                ? "Đã duyệt {$result['approved_count']} ca làm việc."
                : 'Không có ca nào được duyệt.';

            return $this->success($result, $message);
        });
    }

    public function staffSchedules(ListWorkSchedulesRequest $request, string $staffId): JsonResponse
    {
        return $this->tryService(function () use ($request, $staffId) {
            $items = $this->workScheduleService->listForStaff(
                $request->user(),
                $staffId,
                $request->validated()
            );

            return $this->success(
                StaffScheduleResource::collection($items),
                'Lấy lịch nhân viên thành công'
            );
        });
    }

    public function store(StoreWorkScheduleRequest $request): JsonResponse
    {
        $this->authorize('create', StaffSchedule::class);

        return $this->tryService(function () use ($request) {
            $schedule = $this->workScheduleService->create(
                $request->user(),
                $request->validated()
            );

            return $this->created(new StaffScheduleResource($schedule), 'Tạo ca làm việc thành công');
        });
    }

    public function update(UpdateWorkScheduleRequest $request, int $id): JsonResponse
    {
        return $this->tryService(function () use ($request, $id) {
            $existing = StaffSchedule::query()->with('staff.salon')->find($id);
            if (! $existing) {
                abort(404);
            }
            $this->authorize('update', $existing);

            $schedule = $this->workScheduleService->update(
                $request->user(),
                $id,
                $request->validated()
            );

            return $this->success(new StaffScheduleResource($schedule), 'Cập nhật ca làm việc thành công');
        });
    }

    public function destroy(int $id): JsonResponse
    {
        return $this->tryService(function () use ($id) {
            $existing = StaffSchedule::query()->with('staff.salon')->find($id);
            if (! $existing) {
                abort(404);
            }
            $this->authorize('delete', $existing);

            $this->workScheduleService->delete(request()->user(), $id);

            return $this->noContent('Xóa ca làm việc thành công');
        });
    }

    public function approve(ReviewWorkScheduleRequest $request, int $id): JsonResponse
    {
        return $this->tryService(function () use ($request, $id) {
            $existing = StaffSchedule::query()->with('staff.salon')->find($id);
            if (! $existing) {
                abort(404);
            }
            $this->authorize('approve', $existing);

            $schedule = $this->workScheduleService->approve(
                $request->user(),
                $id,
                $request->validated('note')
            );

            return $this->success(new StaffScheduleResource($schedule), 'Đã duyệt ca làm việc');
        });
    }

    public function reject(ReviewWorkScheduleRequest $request, int $id): JsonResponse
    {
        return $this->tryService(function () use ($request, $id) {
            $existing = StaffSchedule::query()->with('staff.salon')->find($id);
            if (! $existing) {
                abort(404);
            }
            $this->authorize('reject', $existing);

            $schedule = $this->workScheduleService->reject(
                $request->user(),
                $id,
                $request->validated('note')
            );

            return $this->success(new StaffScheduleResource($schedule), 'Đã từ chối ca làm việc');
        });
    }
}
