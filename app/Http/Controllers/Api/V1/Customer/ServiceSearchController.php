<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Contracts\Services\Owner\ServiceCatalogServiceInterface;
use App\Http\Controllers\Concerns\PaginatesApiResource;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Customer\SearchServiceRequest;
use App\Http\Resources\Api\V1\Owner\ServiceResource;
use App\Models\Service;
use Illuminate\Http\JsonResponse;

class ServiceSearchController extends Controller
{
    use PaginatesApiResource;

    public function __construct(
        protected ServiceCatalogServiceInterface $serviceCatalog
    ) {}

    public function __invoke(SearchServiceRequest $request): JsonResponse
    {
        $this->authorize('viewAny', Service::class);

        $paginator = $this->serviceCatalog->searchServices($request->validated());

        return $this->paginatedResource($paginator, ServiceResource::class, 'Tìm kiếm dịch vụ thành công');
    }
}
