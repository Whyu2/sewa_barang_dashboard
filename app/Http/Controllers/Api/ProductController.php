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

    public function products(): \Illuminate\Http\JsonResponse
    {
        return $this->success($this->service->all());
    }

    public function productPaginated(Request $request): \Illuminate\Http\JsonResponse
    {
        return $this->success($this->service->paginate($request->limit));
    }

    public function productStore(Request $request)
    {
        try {
            $product = $this->service->create($request->all());
            return $this->success($product, "Product created", 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 422);
        }
    }

    public function productDestroy($id)
    {
        return $this->service->destroy($id);
    }

    public function productUpdate(Request $request, $id)
    {
        return $this->service->update($request->all() , $id);
    }
}
