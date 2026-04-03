<?php

namespace App\Http\Controllers\Api\V1\Owner;

use App\Exceptions\BusinessException;
use App\Http\Controllers\Concerns\PaginatesApiResource;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Owner\BroadcastNotificationRequest;
use App\Http\Requests\Api\V1\Owner\ListOwnerNotificationsRequest;
use App\Http\Requests\Api\V1\Owner\StoreOwnerNotificationRequest;
use App\Http\Requests\Api\V1\Owner\UpdateOwnerNotificationRequest;
use App\Http\Requests\Shared\RouteIdRequest;
use App\Http\Resources\Api\V1\Owner\OwnerNotificationBroadcastResource;
use App\Services\Customer\NotificationService;
use App\Support\NotificationTypes;
use Illuminate\Http\JsonResponse;

class OwnerNotificationController extends Controller
{
    use PaginatesApiResource;

    public function __construct(
        protected NotificationService $notificationService
    ) {}

    public function index(ListOwnerNotificationsRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $perPage = (int) ($validated['per_page'] ?? 20);
        $search = isset($validated['q']) ? trim((string) $validated['q']) : null;

        try {
            $paginator = $this->notificationService->listOwnerHistory(
                $request->user(),
                $perPage,
                is_string($search) ? trim($search) : null,
            );
        } catch (BusinessException $e) {
            return $this->error($e->getMessage(), $e->getCode(), ['code' => $e->getErrorCode()]);
        }

        return $this->paginatedResource(
            $paginator,
            OwnerNotificationBroadcastResource::class,
            'Lấy danh sách thông báo thành công'
        );
    }

    public function show(RouteIdRequest $request, string $id): JsonResponse
    {
        try {
            $broadcast = $this->notificationService->getOwnerNotification($request->user(), $id);
        } catch (BusinessException $e) {
            return $this->error($e->getMessage(), $e->getCode(), ['code' => $e->getErrorCode()]);
        }

        return $this->success(
            new OwnerNotificationBroadcastResource($broadcast),
            'Lấy chi tiết thông báo thành công'
        );
    }

    public function store(StoreOwnerNotificationRequest $request): JsonResponse
    {
        try {
            $broadcast = $this->notificationService->createNotification(
                $request->user(),
                $request->validated(),
            );
        } catch (BusinessException $e) {
            return $this->error($e->getMessage(), $e->getCode(), ['code' => $e->getErrorCode()]);
        }

        return $this->created(
            new OwnerNotificationBroadcastResource($broadcast),
            $this->buildNotificationMessage($broadcast)
        );
    }

    public function update(UpdateOwnerNotificationRequest $request, string $id): JsonResponse
    {
        try {
            $broadcast = $this->notificationService->updateOwnerNotification(
                $request->user(),
                $id,
                $request->validated(),
            );
        } catch (BusinessException $e) {
            return $this->error($e->getMessage(), $e->getCode(), ['code' => $e->getErrorCode()]);
        }

        return $this->success(
            new OwnerNotificationBroadcastResource($broadcast),
            'Cập nhật thông báo thành công'
        );
    }

    public function destroy(RouteIdRequest $request, string $id): JsonResponse
    {
        try {
            $this->notificationService->deleteOwnerNotification($request->user(), $id);
        } catch (BusinessException $e) {
            return $this->error($e->getMessage(), $e->getCode(), ['code' => $e->getErrorCode()]);
        }

        return $this->success(null, 'Xóa thông báo thành công');
    }

    public function broadcast(BroadcastNotificationRequest $request): JsonResponse
    {
        try {
            $broadcast = $this->notificationService->broadcast(
                $request->user(),
                $request->validated('title'),
                $request->validated('content'),
                $request->validated('type') ?? NotificationTypes::GENERAL,
                $request->validated('scheduled_at'),
            );
        } catch (BusinessException $e) {
            return $this->error($e->getMessage(), $e->getCode(), ['code' => $e->getErrorCode()]);
        }

        return $this->created(
            new OwnerNotificationBroadcastResource($broadcast),
            $this->buildNotificationMessage($broadcast)
        );
    }

    public function history(ListOwnerNotificationsRequest $request): JsonResponse
    {
        return $this->index($request);
    }

    protected function buildNotificationMessage($broadcast): string
    {
        if ($broadcast->isPending()) {
            $time = $broadcast->scheduled_at?->timezone('Asia/Ho_Chi_Minh')->format('d/m/Y H:i');

            return "Đã lên lịch gửi thông báo vào {$time}.";
        }

        if ($broadcast->recipient_count > 0) {
            return "Đã gửi thông báo tới {$broadcast->recipient_count} khách hàng.";
        }

        return 'Không có khách hàng nào để gửi (chưa có booking hoặc yêu thích salon).';
    }
}
