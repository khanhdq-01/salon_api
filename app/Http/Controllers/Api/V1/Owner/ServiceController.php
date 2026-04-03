<?php

namespace App\Http\Controllers\Api\V1\Owner;

use App\Contracts\Services\Owner\ServiceCatalogServiceInterface;
use App\Exceptions\BusinessException;
use App\Http\Controllers\Concerns\PaginatesApiResource;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Customer\ListServiceRequest;
use App\Http\Requests\Api\V1\Customer\SearchServiceRequest;
use App\Http\Requests\Api\V1\Owner\StoreServiceRequest;
use App\Http\Requests\Api\V1\Owner\UpdateServiceRequest;
use App\Http\Resources\Api\V1\Owner\ServiceResource;
use App\Models\Service;
use Illuminate\Http\JsonResponse;

class ServiceController extends Controller
{
    use PaginatesApiResource;

    public function __construct(
        protected ServiceCatalogServiceInterface $serviceCatalog
    ) {}

    public function index(ListServiceRequest $request): JsonResponse
    {
        $this->authorize('viewAny', Service::class);

        $paginator = $this->serviceCatalog->listServices($request->validated());

        return $this->paginatedResource($paginator, ServiceResource::class, 'Lấy danh sách dịch vụ thành công');
    }

    public function store(StoreServiceRequest $request): JsonResponse
    {
        $this->authorize('create', Service::class);

        try {
            $service = $this->serviceCatalog->createService($request->validated(), $request->user());
        } catch (BusinessException $e) {
            return $this->error($e->getMessage(), $e->getCode(), ['code' => $e->getErrorCode()]);
        }

        return $this->created(new ServiceResource($service), 'Tạo dịch vụ thành công');
    }

    public function show(string $id): JsonResponse
    {
        try {
            $service = $this->serviceCatalog->getServiceById($id);
        } catch (BusinessException $e) {
            return $this->error($e->getMessage(), $e->getCode(), ['code' => $e->getErrorCode()]);
        }

        $this->authorize('view', $service);

        return $this->success(new ServiceResource($service), 'Lấy chi tiết dịch vụ thành công');
    }

    public function update(UpdateServiceRequest $request, string $id): JsonResponse
    {
        try {
            $service = $this->serviceCatalog->getServiceById($id);
        } catch (BusinessException $e) {
            return $this->error($e->getMessage(), $e->getCode(), ['code' => $e->getErrorCode()]);
        }

        $this->authorize('update', $service);

        try {
            $updated = $this->serviceCatalog->updateService($id, $request->validated(), $request->user());
        } catch (BusinessException $e) {
            return $this->error($e->getMessage(), $e->getCode(), ['code' => $e->getErrorCode()]);
        }

        return $this->success(new ServiceResource($updated), 'Cập nhật dịch vụ thành công');
    }

    public function destroy(string $id): JsonResponse
    {
        try {
            $service = $this->serviceCatalog->getServiceById($id);
        } catch (BusinessException $e) {
            return $this->error($e->getMessage(), $e->getCode(), ['code' => $e->getErrorCode()]);
        }

        $this->authorize('delete', $service);

        try {
            $this->serviceCatalog->deleteService($id, auth()->user());
        } catch (BusinessException $e) {
            return $this->error($e->getMessage(), $e->getCode(), ['code' => $e->getErrorCode()]);
        }

        return $this->noContent('Xóa dịch vụ thành công');
    }
}
