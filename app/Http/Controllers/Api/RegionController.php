<?php
namespace App\Http\Controllers\Api;
use App\Services\RegionService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class RegionController extends BaseController
{
    use ApiResponse;

    public function __construct(
        protected RegionService $service
    )
    {
    }

    public function regions(): \Illuminate\Http\JsonResponse
    {
        return $this->success($this->service->all());
    }

    public function regionPaginated(Request $request): \Illuminate\Http\JsonResponse
    {
        return $this->success($this->service->paginate($request->limit));
    }

    public function regionStore(Request $request)
    {
        try {
            $product = $this->service->create($request->all());
            return $this->success($product, "Region created", 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 422);
        }
    }

    public function regionDestroy($id)
    {
        return $this->service->destroy($id);
    }

    public function regionUpdate(Request $request, $id)
    {
        return $this->service->update($request->all() , $id);
    }
}
