<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Customer\StoreSearchHistoryRequest;
use App\Http\Resources\Api\V1\Customer\SearchHistoryResource;
use App\Models\SearchHistory;
use App\Repositories\Interfaces\Customer\SearchHistoryRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchHistoryController extends Controller
{
    public function __construct(
        protected SearchHistoryRepositoryInterface $searchHistoryRepository,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $items = $this->searchHistoryRepository->getRecent($request->user());

        return $this->success(
            SearchHistoryResource::collection($items),
            'Lấy lịch sử tìm kiếm thành công'
        );
    }

    public function store(StoreSearchHistoryRequest $request): JsonResponse
    {
        $data = $request->validated();

        $item = $data['type'] === SearchHistory::TYPE_SALON
            ? $this->searchHistoryRepository->recordSalon($request->user(), $data['salon_id'])
            : $this->searchHistoryRepository->recordQuery($request->user(), $data['query']);

        return $this->created(
            new SearchHistoryResource($item),
            'Đã lưu lịch sử tìm kiếm'
        );
    }
}
