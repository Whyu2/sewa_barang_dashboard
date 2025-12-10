<?php

namespace App\Http\Controllers\Api;

use App\Services\RentTransactionService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class RentTransactionController extends BaseController
{
    use ApiResponse;

    public function __construct(
        protected RentTransactionService $service
    )
    {
    }

    public function rentTransactions(): \Illuminate\Http\JsonResponse
    {
        return $this->success($this->service->all());
    }

    public function rentTransactionPaginated(Request $request): \Illuminate\Http\JsonResponse
    {
        return $this->success($this->service->paginate($request->limit));
    }

    public function rentTransactionStore(Request $request)
    {
        try {
            $product = $this->service->create($request->all());
            return $this->success($product, "Transaction created", 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 422);
        }
    }

    public function rentTransactionDestroy($id)
    {
        return $this->service->destroy($id);
    }

    public function rentTransactionUpdate(Request $request, $id)
    {
        return $this->service->update($request->all(), $id);
    }
}
