<?php

namespace App\Http\Controllers\Api\V1\Owner;

use App\Exceptions\BusinessException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Owner\ListStyleOptionRequest;
use App\Http\Requests\Api\V1\Owner\StoreStyleOptionRequest;
use App\Http\Requests\Api\V1\Owner\UpdateStyleOptionRequest;
use App\Http\Resources\Api\V1\Owner\ServiceStyleOptionResource;
use App\Models\ServiceStyleOption;
use App\Services\Owner\StyleOptionService;
use Illuminate\Http\JsonResponse;

class StyleOptionController extends Controller
{
    public function __construct(
        protected StyleOptionService $styleOptionService,
    ) {}

    public function index(ListStyleOptionRequest $request): JsonResponse
    {
        $this->authorize('viewAny', ServiceStyleOption::class);

        try {
            $items = $this->styleOptionService->listBySalon(
                $request->validated('salon_id'),
                $request->user(),
            );
        } catch (BusinessException $e) {
            return $this->error($e->getMessage(), $e->getCode(), ['code' => $e->getErrorCode()]);
        }

        return $this->success(
            ServiceStyleOptionResource::collection($items),
            'Lấy danh sách hair style options thành công',
        );
    }

    public function show(string $id): JsonResponse
    {
        try {
            $styleOption = $this->styleOptionService->findOrFail($id);
        } catch (BusinessException $e) {
            return $this->error($e->getMessage(), $e->getCode(), ['code' => $e->getErrorCode()]);
        }

        $this->authorize('view', $styleOption);

        return $this->success(new ServiceStyleOptionResource($styleOption), 'Lấy chi tiết hair style option thành công');
    }

    public function store(StoreStyleOptionRequest $request): JsonResponse
    {
        $this->authorize('create', ServiceStyleOption::class);

        try {
            $styleOption = $this->styleOptionService->create($request->validated(), $request->user());
        } catch (BusinessException $e) {
            return $this->error($e->getMessage(), $e->getCode(), ['code' => $e->getErrorCode()]);
        }

        return $this->created(new ServiceStyleOptionResource($styleOption), 'Tạo hair style option thành công');
    }

    public function update(UpdateStyleOptionRequest $request, string $id): JsonResponse
    {
        try {
            $styleOption = $this->styleOptionService->findOrFail($id);
        } catch (BusinessException $e) {
            return $this->error($e->getMessage(), $e->getCode(), ['code' => $e->getErrorCode()]);
        }

        $this->authorize('update', $styleOption);

        try {
            $updated = $this->styleOptionService->update($id, $request->validated(), $request->user());
        } catch (BusinessException $e) {
            return $this->error($e->getMessage(), $e->getCode(), ['code' => $e->getErrorCode()]);
        }

        return $this->success(new ServiceStyleOptionResource($updated), 'Cập nhật hair style option thành công');
    }

    public function destroy(string $id): JsonResponse
    {
        try {
            $styleOption = $this->styleOptionService->findOrFail($id);
        } catch (BusinessException $e) {
            return $this->error($e->getMessage(), $e->getCode(), ['code' => $e->getErrorCode()]);
        }

        $this->authorize('delete', $styleOption);

        try {
            $this->styleOptionService->delete($id, auth()->user());
        } catch (BusinessException $e) {
            return $this->error($e->getMessage(), $e->getCode(), ['code' => $e->getErrorCode()]);
        }

        return $this->noContent('Xóa hair style option thành công');
    }
}
