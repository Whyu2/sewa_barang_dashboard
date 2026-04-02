<?php
namespace App\Http\Controllers\Api;
use App\Services\ProductService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class ProductController extends BaseController
{
    use ApiResponse;

    public function __construct(
        protected ProductService $service
    )
    {
    }

    #[OA\Get(
        path: "/products",
        operationId: "getProductList",
        tags: ["Products"],
        summary: "Mendapatkan daftar semua produk",
        description: "Mengembalikan array berisi semua data produk",
        security: [["bearerAuth" => []]],
        responses: [
            new OA\Response(response: 200, description: "Berhasil mengambil data"),
            new OA\Response(response: 401, description: "Unauthenticated")
        ]
    )]
    public function products(): \Illuminate\Http\JsonResponse
    {
        return $this->success($this->service->all());
    }

    #[OA\Get(
        path: "/products-paginated",
        operationId: "getProductPaginated",
        tags: ["Products"],
        summary: "Daftar produk (Paginated)",
        description: "Mengambil daftar produk dengan sistem pagination",
        security: [["bearerAuth" => []]],
        parameters: [
            new OA\Parameter(name: "limit", in: "query", description: "Jumlah data per halaman", required: false, schema: new OA\Schema(type: "integer", example: 10))
        ],
        responses: [
            new OA\Response(response: 200, description: "Berhasil mengambil data"),
            new OA\Response(response: 401, description: "Unauthenticated")
        ]
    )]
    public function productPaginated(Request $request): \Illuminate\Http\JsonResponse
    {
        return $this->success($this->service->paginate($request->limit));
    }

    #[OA\Post(
        path: "/products",
        operationId: "storeProduct",
        tags: ["Products"],
        summary: "Menambahkan produk baru",
        description: "Membuat data produk baru ke dalam database",
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["name", "category_id", "price"],
                properties: [
                    new OA\Property(property: "name", type: "string", example: "Barang A"),
                    new OA\Property(property: "category_id", type: "integer", example: 1),
                    new OA\Property(property: "price", type: "number", example: 50000)
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: "Produk berhasil dibuat"),
            new OA\Response(response: 422, description: "Validasi gagal")
        ]
    )]
    public function productStore(Request $request)
    {
        try {
            $product = $this->service->create($request->all());
            return $this->success($product, "Product created", 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 422);
        }
    }

    #[OA\Delete(
        path: "/products/{id}",
        operationId: "destroyProduct",
        tags: ["Products"],
        summary: "Hapus produk",
        description: "Menghapus produk berdasarkan ID",
        security: [["bearerAuth" => []]],
        parameters: [
            new OA\Parameter(name: "id", in: "path", description: "ID Produk", required: true, schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(response: 200, description: "Produk berhasil dihapus"),
            new OA\Response(response: 401, description: "Unauthenticated")
        ]
    )]
    public function productDestroy($id)
    {
        return $this->service->destroy($id);
    }

    #[OA\Put(
        path: "/products/{id}",
        operationId: "updateProduct",
        tags: ["Products"],
        summary: "Update produk",
        description: "Memperbarui data produk berdasarkan ID",
        security: [["bearerAuth" => []]],
        parameters: [
            new OA\Parameter(name: "id", in: "path", description: "ID Produk", required: true, schema: new OA\Schema(type: "integer"))
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "name", type: "string", example: "Barang Updated"),
                    new OA\Property(property: "category_id", type: "integer", example: 1),
                    new OA\Property(property: "price", type: "number", example: 60000)
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: "Produk berhasil diperbarui"),
            new OA\Response(response: 401, description: "Unauthenticated")
        ]
    )]
    public function productUpdate(Request $request, $id)
    {
        return $this->service->update($request->all() , $id);
    }


    #[OA\Get(
        path: "/products/find-by-qr",
        operationId: "productFindByQrCode",
        tags: ["Products"],
        summary: "Cari produk dengan QR Code",
        description: "Mencari data produk berdasarkan UUID QR Code",
        security: [["bearerAuth" => []]],
        parameters: [
            new OA\Parameter(name: "qr_uuid", in: "query", description: "UUID QR Code", required: true, schema: new OA\Schema(type: "string"))
        ],
        responses: [
            new OA\Response(response: 200, description: "Data produk ditemukan"),
            new OA\Response(response: 422, description: "Data tidak ditemukan"),
            new OA\Response(response: 401, description: "Unauthenticated")
        ]
    )]
    public function productFindByQrCode(Request $request)
    {
        try {
        $product = $this->service->findByQrCode($request->qr_uuid);
        return $this->success($product);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 422);
        }
    
    }
}
