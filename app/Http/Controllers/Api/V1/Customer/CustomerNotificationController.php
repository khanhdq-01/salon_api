<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Http\Controllers\Concerns\PaginatesApiResource;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Customer\ListCustomerShopNotificationsRequest;
use App\Http\Requests\Api\V1\Customer\MarkCustomerNotificationsReadRequest;
use App\Http\Resources\Api\V1\Customer\CustomerNotificationResource;
use App\Models\User;
use App\Services\Customer\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class CustomerNotificationController extends Controller
{
    use PaginatesApiResource;

    public function __construct(
        protected NotificationService $notificationService
    ) {}

    public function shop(ListCustomerShopNotificationsRequest $request): JsonResponse
    {
        $perPage = (int) ($request->validated('per_page') ?? 20);
        $user = $this->resolveOptionalCustomer($request);

        $paginator = $this->notificationService->listShopNotifications($user, $perPage);

        return $this->paginatedResource(
            $paginator,
            CustomerNotificationResource::class,
            'Lấy thông báo từ cửa hàng thành công'
        );
    }

    public function unreadCount(Request $request): JsonResponse
    {
        $count = $this->notificationService->countUnreadShopNotifications($request->user());

        return $this->success(
            ['unread_count' => $count],
            'Lấy số thông báo chưa đọc thành công'
        );
    }

    public function markRead(MarkCustomerNotificationsReadRequest $request): JsonResponse
    {
        $markedCount = $this->notificationService->markShopNotificationsAsRead(
            $request->user(),
            $request->validated('ids') ?? null,
        );

        return $this->success(
            ['marked_count' => $markedCount],
            'Đã đánh dấu thông báo là đã đọc'
        );
    }

    protected function resolveOptionalCustomer(Request $request): ?User
    {
        if (! $request->bearerToken()) {
            return null;
        }

        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (\Throwable) {
            return null;
        }

        if (! $user instanceof User || ! $user->isCustomer()) {
            return null;
        }

        return $user;
    }
}
