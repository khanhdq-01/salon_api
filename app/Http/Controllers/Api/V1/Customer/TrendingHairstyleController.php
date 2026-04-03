<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Customer\ListTrendingHairstylesRequest;
use App\Http\Resources\Api\V1\Customer\TrendingHairstyleResource;
use App\Services\Customer\TrendingHairstyleService;
use Illuminate\Http\JsonResponse;

class TrendingHairstyleController extends Controller
{
    public function __construct(
        protected TrendingHairstyleService $trendingHairstyleService,
    ) {}

    public function index(ListTrendingHairstylesRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $limit = (int) ($validated['limit'] ?? 24);
        $gender = $validated['gender'] ?? null;

        $items = $this->trendingHairstyleService->listByBookingCount($limit, $gender);

        return $this->success(
            TrendingHairstyleResource::collection($items),
            'Lấy kiểu tóc thịnh hành thành công',
        );
    }
}
