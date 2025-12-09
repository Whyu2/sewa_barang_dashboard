<?php
namespace App\Http\Controllers\Api;
use App\Services\CategoryService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class CategoryController extends BaseController
{
    use ApiResponse;

    public function __construct(
        protected CategoryService $service
    )
    {
    }

    public function categories(): \Illuminate\Http\JsonResponse
    {
        return $this->success($this->service->getAllCategories());
    }

    public function categoryPaginated(Request $request): \Illuminate\Http\JsonResponse
    {
        return $this->success($this->service->getPaginatedCategories($request->limit));
    }

    public function categoryStore(Request $request)
    {
        try {
            $product = $this->service->createCategory($request->all());
            return $this->success($product, "Category created", 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 422);
        }
    }

    public function categoryDestroy($id)
    {
        return $this->service->destroyCategory($id);
    }

    public function categoryUpdate(Request $request, $id)
    {
        return $this->service->updateCategory($request->all() , $id);
    }
}
