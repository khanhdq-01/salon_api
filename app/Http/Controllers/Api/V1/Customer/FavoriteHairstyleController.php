<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Customer\StoreFavoriteHairstyleRequest;
use App\Http\Requests\Shared\RouteStyleIdRequest;
use App\Http\Resources\Api\V1\Customer\SalonHairstyleResource;
use App\Models\FavoriteProduct;
use App\Models\ServiceStyleOption;
use App\Repositories\Interfaces\Customer\FavoriteRepositoryInterface;
use App\Repositories\Interfaces\Customer\ServiceStyleOptionRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FavoriteHairstyleController extends Controller
{
    public function __construct(
        protected FavoriteRepositoryInterface $favoriteRepository,
        protected ServiceStyleOptionRepositoryInterface $styleOptionRepository,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $refs = $this->favoriteRepository->getFavoriteHairstyleRefs($request->user());

        if ($refs->isEmpty()) {
            return $this->success([], 'Lấy danh sách kiểu tóc yêu thích thành công');
        }

        $items = $this->styleOptionRepository->getActiveByIdsForPublicSalons($refs->all())
            ->sortBy(fn (ServiceStyleOption $item) => $refs->search($item->id))
            ->values();

        return $this->success(
            SalonHairstyleResource::collection($items),
            'Lấy danh sách kiểu tóc yêu thích thành công',
        );
    }

    public function store(StoreFavoriteHairstyleRequest $request): JsonResponse
    {
        $style = $this->styleOptionRepository->findActivePublicById($request->validated('style_option_id'));

        if (! $style) {
            return $this->error('Kiểu tóc không khả dụng.', 404);
        }

        $request->user()->favoriteProducts()->firstOrCreate([
            'product_type' => FavoriteProduct::TYPE_HAIRSTYLE,
            'product_ref' => $style->id,
        ]);

        return $this->created(new SalonHairstyleResource($style), 'Đã thêm vào yêu thích');
    }

    public function destroy(RouteStyleIdRequest $request, string $styleId): JsonResponse
    {
        $request->user()->favoriteProducts()
            ->ofType(FavoriteProduct::TYPE_HAIRSTYLE)
            ->where('product_ref', $styleId)
            ->delete();

        return $this->success(null, 'Đã bỏ yêu thích');
    }
}
