<?php

namespace App\Http\Controllers\Api\V1\Owner;

use App\Exceptions\BusinessException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Owner\UpdateSalonStatusRequest;
use App\Http\Resources\Api\V1\Customer\SalonResource;
use App\Contracts\Services\Owner\SalonServiceInterface;
use Illuminate\Http\JsonResponse;

class SalonStatusController extends Controller
{
    public function __construct(
        protected SalonServiceInterface $salonService
    ) {}

    public function update(UpdateSalonStatusRequest $request, string $id): JsonResponse
    {
        try {
            $salon = $this->salonService->findSalonOrFail($id);
        } catch (BusinessException $e) {
            return $this->error($e->getMessage(), $e->getCode(), ['code' => $e->getErrorCode()]);
        }

        $this->authorize('updateStatus', $salon);

        try {
            $updated = $this->salonService->updateSalonStatus(
                $id,
                $request->validated(),
                $request->user()
            );
        } catch (BusinessException $e) {
            return $this->error($e->getMessage(), $e->getCode(), ['code' => $e->getErrorCode()]);
        }

        return $this->success(new SalonResource($updated), 'Cập nhật trạng thái salon thành công');
    }
}
