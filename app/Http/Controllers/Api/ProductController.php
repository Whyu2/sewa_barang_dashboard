<?php
namespace App\Http\Controllers\Api;
use App\Services\ProductService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class ProductController extends BaseController
{
    use ApiResponse;

    public function __construct(
        protected ProductService $service
    )
    {
    }

    public function index(): \Illuminate\Http\JsonResponse
    {
        return $this->success($this->service->getPaginatedProducts(10));
    }

    public function store(Request $request)
    {
        try {
            $product = $this->service->createProduct($request->all());
            return $this->success($product, "Product created", 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 422);
        }
    }
}
