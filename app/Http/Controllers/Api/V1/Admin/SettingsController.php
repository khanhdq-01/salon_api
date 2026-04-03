<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Contracts\Services\Admin\AdminSettingsServiceInterface;
use App\Http\Controllers\Concerns\HandlesServiceException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Admin\UpdateAdminSettingsRequest;
use Illuminate\Http\JsonResponse;

class SettingsController extends Controller
{
    use HandlesServiceException;

    public function __construct(
        protected AdminSettingsServiceInterface $settingsService
    ) {}

    public function show(): JsonResponse
    {
        return $this->tryService(
            fn () => $this->settingsService->getSettings(),
            'Lấy cài đặt hệ thống thành công',
        );
    }

    public function update(UpdateAdminSettingsRequest $request): JsonResponse
    {
        return $this->tryService(
            fn () => $this->settingsService->updateSettings($request->validated()),
            'Cập nhật cài đặt hệ thống thành công',
        );
    }
}
