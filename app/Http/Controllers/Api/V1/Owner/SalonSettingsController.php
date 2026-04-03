<?php

namespace App\Http\Controllers\Api\V1\Owner;

use App\Contracts\Services\Owner\OwnerSalonSettingsServiceInterface;
use App\Exceptions\BusinessException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Owner\UpdateOwnerSalonSettingsRequest;
use App\Http\Resources\Api\V1\Owner\SalonSettingsResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SalonSettingsController extends Controller
{
    public function __construct(
        protected OwnerSalonSettingsServiceInterface $settingsService
    ) {}

    public function show(Request $request): JsonResponse
    {
        try {
            $settings = $this->settingsService->getForOwner($request->user());
        } catch (BusinessException $e) {
            return $this->error($e->getMessage(), $e->getCode(), ['code' => $e->getErrorCode()]);
        }

        return $this->success(new SalonSettingsResource($settings), 'Lấy cài đặt salon thành công');
    }

    public function update(UpdateOwnerSalonSettingsRequest $request): JsonResponse
    {
        try {
            $settings = $this->settingsService->updateForOwner(
                $request->user(),
                $request->validated()
            );
        } catch (BusinessException $e) {
            return $this->error($e->getMessage(), $e->getCode(), ['code' => $e->getErrorCode()]);
        }

        return $this->success(new SalonSettingsResource($settings), 'Cập nhật cài đặt salon thành công');
    }
}
