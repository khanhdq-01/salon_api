<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Http\Controllers\Concerns\PaginatesSalonCollection;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Customer\ListSalonRequest;
use App\Models\Salon;
use App\Contracts\Services\Owner\SalonServiceInterface;
use Illuminate\Http\JsonResponse;

class SalonSearchController extends Controller
{
    use PaginatesSalonCollection;

    public function __construct(
        protected SalonServiceInterface $salonService
    ) {}

    public function search(ListSalonRequest $request): JsonResponse
    {
        $this->authorize('viewAny', Salon::class);

        $paginator = $this->salonService->listSalons(
            $request->validated(),
            $request->user()
        );

        return $this->paginatedSalons($paginator, 'Tìm kiếm salon thành công');
    }
}
