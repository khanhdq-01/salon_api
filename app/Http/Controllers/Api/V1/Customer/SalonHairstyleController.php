<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Customer\ShowSalonHairstyleRequest;
use App\Http\Requests\Shared\RouteSalonIdRequest;
use App\Http\Resources\Api\V1\Customer\SalonHairstyleResource;
use App\Repositories\Interfaces\Owner\SalonRepositoryInterface;
use App\Repositories\Interfaces\Customer\ServiceStyleOptionRepositoryInterface;
use App\Support\SalonVisibility;
use Illuminate\Http\JsonResponse;

class SalonHairstyleController extends Controller
{
    public function __construct(
        protected SalonRepositoryInterface $salonRepository,
        protected ServiceStyleOptionRepositoryInterface $styleOptionRepository,
    ) {}

    public function index(RouteSalonIdRequest $request, string $salonId): JsonResponse
    {
        $salon = $this->salonRepository->findById($salonId);

        if (! $salon) {
            return $this->notFound('Salon không tồn tại.');
        }

        try {
            SalonVisibility::assertCustomerAccessible($salon, auth()->user());
        } catch (\App\Exceptions\BusinessException $e) {
            return $this->error($e->getMessage(), $e->getCode(), ['code' => $e->getErrorCode()]);
        }

        $items = $this->styleOptionRepository->getFeaturedBySalon($salonId);

        return $this->success(
            SalonHairstyleResource::collection($items),
            'Lấy danh sách kiểu tóc nổi bật thành công',
        );
    }

    public function show(ShowSalonHairstyleRequest $request, string $salonId, string $styleId): JsonResponse
    {
        $salon = $this->salonRepository->findById($salonId);

        if (! $salon) {
            return $this->notFound('Salon không tồn tại.');
        }

        try {
            SalonVisibility::assertCustomerAccessible($salon, auth()->user());
        } catch (\App\Exceptions\BusinessException $e) {
            return $this->error($e->getMessage(), $e->getCode(), ['code' => $e->getErrorCode()]);
        }

        $style = $this->styleOptionRepository->findActiveBySalonAndId($salonId, $styleId);

        if (! $style) {
            return $this->notFound('Không tìm thấy kiểu tóc.');
        }

        return $this->success(new SalonHairstyleResource($style), 'Lấy chi tiết kiểu tóc thành công');
    }
}
