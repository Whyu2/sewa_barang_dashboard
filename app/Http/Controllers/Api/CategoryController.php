<?php
namespace App\Http\Controllers\Api;
use App\Services\CategoryService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class CategoryController extends BaseController
{
    use ApiResponse;

    public function __construct(
        protected CategoryService $service
    )
    {
    }

    #[OA\Get(
        path: "/categories",
        operationId: "getCategoryList",
        tags: ["Categories"],
        summary: "Daftar kategori",
        description: "Mengambil semua daftar kategori barang",
        security: [["bearerAuth" => []]],
        responses: [
            new OA\Response(response: 200, description: "Berhasil mengambil data"),
            new OA\Response(response: 401, description: "Unauthenticated")
        ]
    )]
    public function categories(): \Illuminate\Http\JsonResponse
    {
        return $this->success($this->service->all());
    }

    #[OA\Get(
        path: "/categories-paginated",
        operationId: "getCategoryPaginated",
        tags: ["Categories"],
        summary: "Daftar kategori (Paginated)",
        description: "Mengambil daftar kategori dengan sistem pagination",
        security: [["bearerAuth" => []]],
        parameters: [
            new OA\Parameter(name: "limit", in: "query", description: "Jumlah data per halaman", required: false, schema: new OA\Schema(type: "integer", example: 10))
        ],
        responses: [
            new OA\Response(response: 200, description: "Berhasil mengambil data"),
            new OA\Response(response: 401, description: "Unauthenticated")
        ]
    )]
    public function categoryPaginated(Request $request): \Illuminate\Http\JsonResponse
    {
        return $this->success($this->service->paginate($request->limit));
    }

    #[OA\Post(
        path: "/categories",
        operationId: "storeCategory",
        tags: ["Categories"],
        summary: "Buat kategori baru",
        description: "Menambahkan kategori barang baru",
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["name"],
                properties: [
                    new OA\Property(property: "name", type: "string", example: "Elektronik")
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: "Kategori berhasil dibuat"),
            new OA\Response(response: 422, description: "Validasi gagal"),
            new OA\Response(response: 401, description: "Unauthenticated")
        ]
    )]
    public function categoryStore(Request $request)
    {
        try {
            $product = $this->service->create($request->all());
            return $this->success($product, "Category created", 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 422);
        }
    }

    #[OA\Delete(
        path: "/categories/{id}",
        operationId: "destroyCategory",
        tags: ["Categories"],
        summary: "Hapus kategori",
        description: "Menghapus kategori berdasarkan ID",
        security: [["bearerAuth" => []]],
        parameters: [
            new OA\Parameter(name: "id", in: "path", description: "ID Kategori", required: true, schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(response: 200, description: "Kategori berhasil dihapus"),
            new OA\Response(response: 401, description: "Unauthenticated")
        ]
    )]
    public function categoryDestroy($id)
    {
        return $this->service->destroy($id);
    }

    #[OA\Put(
        path: "/categories/{id}",
        operationId: "updateCategory",
        tags: ["Categories"],
        summary: "Update kategori",
        description: "Memperbarui data kategori berdasarkan ID",
        security: [["bearerAuth" => []]],
        parameters: [
            new OA\Parameter(name: "id", in: "path", description: "ID Kategori", required: true, schema: new OA\Schema(type: "integer"))
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "name", type: "string", example: "Elektronik Updated")
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: "Kategori berhasil diperbarui"),
            new OA\Response(response: 401, description: "Unauthenticated")
        ]
    )]
    public function categoryUpdate(Request $request, $id)
    {
        return $this->service->update($request->all() , $id);
    }
}
