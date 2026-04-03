<?php

namespace App\Http\Controllers\Api\V1\Owner;

use App\Exceptions\BusinessException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Owner\StoreSalonRequest;
use App\Http\Requests\Api\V1\Owner\UpdateSalonRequest;
use App\Http\Resources\Api\V1\Customer\SalonResource;
use App\Models\Salon;
use App\Contracts\Services\Owner\SalonServiceInterface;
use Illuminate\Http\JsonResponse;

class SalonController extends Controller
{
    public function __construct(
        protected SalonServiceInterface $salonService
    ) {}

    public function show(string $id): JsonResponse
    {
        try {
            $salon = $this->salonService->getSalonById($id, auth()->user());
        } catch (BusinessException $e) {
            return $this->error($e->getMessage(), $e->getCode(), ['code' => $e->getErrorCode()]);
        }

        $this->authorize('view', $salon);

        return $this->success(new SalonResource($salon), 'Lấy chi tiết salon thành công');
    }

    public function store(StoreSalonRequest $request): JsonResponse
    {
        $this->authorize('create', Salon::class);

        $payload = $request->validated();

        if ($request->user()->isOwner()) {
            $payload['owner_id'] = $request->user()->id;
        }

        try {
            $salon = $this->salonService->createSalon($payload, $request->user());
        } catch (BusinessException $e) {
            return $this->error($e->getMessage(), $e->getCode(), ['code' => $e->getErrorCode()]);
        }

        $message = $request->user()->isOwner()
            ? 'Salon đã được tạo, đang chờ Admin duyệt'
            : 'Tạo salon thành công';

        return $this->created(new SalonResource($salon), $message);
    }

    public function update(UpdateSalonRequest $request, string $id): JsonResponse
    {
        try {
            $salon = $this->salonService->findSalonOrFail($id);
        } catch (BusinessException $e) {
            return $this->error($e->getMessage(), $e->getCode(), ['code' => $e->getErrorCode()]);
        }

        $this->authorize('update', $salon);

        try {
            $updated = $this->salonService->updateSalon(
                $id,
                $request->validated(),
                $request->user()
            );
        } catch (BusinessException $e) {
            return $this->error($e->getMessage(), $e->getCode(), ['code' => $e->getErrorCode()]);
        }

        return $this->success(new SalonResource($updated), 'Cập nhật salon thành công');
    }

    public function destroy(string $id): JsonResponse
    {
        try {
            $salon = $this->salonService->findSalonOrFail($id);
        } catch (BusinessException $e) {
            return $this->error($e->getMessage(), $e->getCode(), ['code' => $e->getErrorCode()]);
        }

        $this->authorize('delete', $salon);

        try {
            $this->salonService->deleteSalon($id, auth()->user());
        } catch (BusinessException $e) {
            return $this->error($e->getMessage(), $e->getCode(), ['code' => $e->getErrorCode()]);
        }

        return $this->noContent('Xóa salon thành công');
    }
}
