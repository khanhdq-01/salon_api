<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Contracts\Services\Admin\AdminPaymentInstructionManagementServiceInterface;
use App\Http\Controllers\Concerns\HandlesServiceException;
use App\Http\Controllers\Concerns\PaginatesApiResource;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Admin\ListAdminPaymentInstructionsRequest;
use App\Http\Requests\Api\V1\Admin\StoreAdminPaymentInstructionRequest;
use App\Http\Requests\Api\V1\Admin\UpdateAdminPaymentInstructionRequest;
use App\Http\Requests\Shared\RouteIdRequest;
use App\Http\Resources\Api\V1\Admin\AdminPaymentInstructionResource;
use Illuminate\Http\JsonResponse;

class PaymentInstructionManagementController extends Controller
{
    use HandlesServiceException, PaginatesApiResource;

    public function __construct(
        protected AdminPaymentInstructionManagementServiceInterface $paymentInstructionService
    ) {}

    public function index(ListAdminPaymentInstructionsRequest $request): JsonResponse
    {
        $paginator = $this->paymentInstructionService->listInstructions($request->validated());

        return $this->paginatedResource($paginator, AdminPaymentInstructionResource::class, 'Lấy danh sách payment instructions thành công');
    }

    public function show(RouteIdRequest $request, string $id): JsonResponse
    {
        return $this->tryService(
            fn () => new AdminPaymentInstructionResource($this->paymentInstructionService->findOrFail($id)),
            'Lấy payment instruction thành công',
        );
    }

    public function store(StoreAdminPaymentInstructionRequest $request): JsonResponse
    {
        return $this->tryService(
            fn () => new AdminPaymentInstructionResource($this->paymentInstructionService->createInstruction($request->validated())),
            'Tạo payment instruction thành công',
        );
    }

    public function update(UpdateAdminPaymentInstructionRequest $request, string $id): JsonResponse
    {
        return $this->tryService(
            fn () => new AdminPaymentInstructionResource($this->paymentInstructionService->updateInstruction($id, $request->validated())),
            'Cập nhật payment instruction thành công',
        );
    }

    public function activate(string $id): JsonResponse
    {
        return $this->tryService(
            fn () => new AdminPaymentInstructionResource($this->paymentInstructionService->activateInstruction($id)),
            'Kích hoạt payment instruction thành công',
        );
    }

    public function destroy(RouteIdRequest $request, string $id): JsonResponse
    {
        return $this->tryService(
            fn () => $this->paymentInstructionService->deleteInstruction($id),
            'Xóa payment instruction thành công',
        );
    }
}
