<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Customer\StoreFavoriteSalonRequest;
use App\Http\Requests\Shared\RouteSalonIdRequest;
use App\Http\Resources\Api\V1\Customer\SalonResource;
use App\Repositories\Interfaces\Customer\FavoriteRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FavoriteSalonController extends Controller
{
    public function __construct(
        protected FavoriteRepositoryInterface $favoriteRepository,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $salons = $this->favoriteRepository->getFavoriteSalons($request->user());

        return $this->success(
            SalonResource::collection($salons),
            'Lấy danh sách cửa hàng yêu thích thành công'
        );
    }

    public function store(StoreFavoriteSalonRequest $request): JsonResponse
    {
        $salon = $this->favoriteRepository->findPublicSalonById($request->validated('salon_id'));

        if (! $salon) {
            return $this->error('Salon không khả dụng.', 404);
        }

        $request->user()->favoriteSalons()->syncWithoutDetaching([$salon->id]);

        return $this->created(new SalonResource($salon), 'Đã thêm vào yêu thích');
    }

    public function destroy(RouteSalonIdRequest $request, string $salonId): JsonResponse
    {
        $request->user()->favoriteSalons()->detach($salonId);

        return $this->success(null, 'Đã bỏ yêu thích');
    }
}
