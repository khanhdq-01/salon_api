<?php

namespace App\Http\Controllers\Api\V1\Owner;

use App\Contracts\Services\Owner\SalonServiceInterface;
use App\Exceptions\BusinessException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Owner\UploadSalonImageRequest;
use App\Http\Requests\Shared\RouteImageIdRequest;
use App\Http\Resources\Api\V1\Customer\SalonImageResource;
use App\Support\ImageUploadStorage;
use Illuminate\Http\JsonResponse;

class SalonImageController extends Controller
{
    public function __construct(
        protected SalonServiceInterface $salonService
    ) {}

    public function store(UploadSalonImageRequest $request): JsonResponse
    {
        try {
            $salon = $this->salonService->getOwnerSalon($request->user());
        } catch (BusinessException $e) {
            return $this->error($e->getMessage(), $e->getCode(), ['code' => $e->getErrorCode()]);
        }

        $this->authorize('update', $salon);

        $stored = ImageUploadStorage::store(
            $request->file('image'),
            ImageUploadStorage::DIR_SALON_GALLERY
        );
        $imageUrl = $stored['url'];

        $image = $salon->images()->create([
            'image_url' => $imageUrl,
        ]);

        $salon->update(['image_url' => $imageUrl]);

        return $this->created(
            new SalonImageResource($image),
            'Tải ảnh salon thành công'
        );
    }

    public function destroy(RouteImageIdRequest $request, string $imageId): JsonResponse
    {
        try {
            $salon = $this->salonService->getOwnerSalon($request->user());
        } catch (BusinessException $e) {
            return $this->error($e->getMessage(), $e->getCode(), ['code' => $e->getErrorCode()]);
        }

        $this->authorize('update', $salon);

        $image = $salon->images()->whereKey($imageId)->first();

        if (! $image) {
            return $this->error('Ảnh salon không tồn tại.', 404, ['code' => 'SALON_IMAGE_NOT_FOUND']);
        }

        $image->delete();

        $latestImage = $salon->images()->orderByDesc('created_at')->value('image_url');
        $salon->update(['image_url' => $latestImage]);

        return $this->success(null, 'Xóa ảnh salon thành công');
    }
}
