<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Customer\ListPopularServicesRequest;
use App\Http\Resources\Api\V1\Customer\PopularServiceResource;
use App\Models\Service;
use App\Services\Owner\PopularServiceService;
use Illuminate\Http\JsonResponse;

class PopularServiceController extends Controller
{
    public function __construct(
        protected PopularServiceService $popularServiceService,
    ) {}

    public function __invoke(ListPopularServicesRequest $request): JsonResponse
    {
        $this->authorize('viewAny', Service::class);

        $limit = (int) ($request->validated('limit') ?? 8);
        $items = $this->popularServiceService->listPopular($limit);

        return $this->success(
            PopularServiceResource::collection($items),
            'Lấy dịch vụ phổ biến thành công',
        );
    }
}
