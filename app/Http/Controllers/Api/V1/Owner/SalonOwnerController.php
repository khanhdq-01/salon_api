<?php

namespace App\Http\Controllers\Api\V1\Owner;

use App\Exceptions\BusinessException;
use App\Http\Controllers\Concerns\PaginatesSalonCollection;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Admin\ListOwnerSalonsRequest;
use App\Http\Requests\Api\V1\Owner\StoreSalonRequest;
use App\Http\Requests\Api\V1\Owner\UpdateSalonRequest;
use App\Http\Resources\Api\V1\Customer\SalonResource;
use App\Models\Salon;
use App\Contracts\Services\Owner\SalonServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SalonOwnerController extends Controller
{
    use PaginatesSalonCollection;

    public function __construct(
        protected SalonServiceInterface $salonService
    ) {}

    public function mine(Request $request): JsonResponse
    {
        try {
            $salon = $this->salonService->getOwnerSalon($request->user());
        } catch (BusinessException $e) {
            return $this->error($e->getMessage(), $e->getCode(), ['code' => $e->getErrorCode()]);
        }

        $this->authorize('view', $salon);

        return $this->success(new SalonResource($salon), 'Lấy salon của owner thành công');
    }

    public function storeMine(StoreSalonRequest $request): JsonResponse
    {
        $this->authorize('create', Salon::class);

        try {
            $salon = $this->salonService->createSalon($request->validated(), $request->user());
        } catch (BusinessException $e) {
            return $this->error($e->getMessage(), $e->getCode(), ['code' => $e->getErrorCode()]);
        }

        return $this->created(new SalonResource($salon), 'Salon đã được tạo, đang chờ Admin duyệt');
    }

    public function updateMine(UpdateSalonRequest $request): JsonResponse
    {
        try {
            $salon = $this->salonService->getOwnerSalon($request->user());
        } catch (BusinessException $e) {
            return $this->error($e->getMessage(), $e->getCode(), ['code' => $e->getErrorCode()]);
        }

        $this->authorize('update', $salon);

        try {
            $updated = $this->salonService->updateSalon(
                $salon->id,
                $request->validated(),
                $request->user()
            );
        } catch (BusinessException $e) {
            return $this->error($e->getMessage(), $e->getCode(), ['code' => $e->getErrorCode()]);
        }

        return $this->success(new SalonResource($updated), 'Cập nhật salon thành công');
    }

    public function index(ListOwnerSalonsRequest $request, string $ownerId): JsonResponse
    {
        $filters = array_merge(
            $request->validated(),
            ['owner_id' => $ownerId, 'public_only' => false]
        );

        $paginator = $this->salonService->listSalons($filters, $request->user());

        return $this->paginatedSalons($paginator, 'Lấy salon theo owner thành công');
    }
}
