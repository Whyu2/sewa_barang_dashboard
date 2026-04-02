<?php
namespace App\Http\Controllers\Api;
use App\Services\RegionService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class RegionController extends BaseController
{
    use ApiResponse;

    public function __construct(
        protected RegionService $service
    )
    {
    }

    #[OA\Get(
        path: "/regions",
        operationId: "getRegionList",
        tags: ["Regions"],
        summary: "Daftar wilayah",
        description: "Mengambil semua daftar wilayah/lokasi",
        security: [["bearerAuth" => []]],
        responses: [
            new OA\Response(response: 200, description: "Berhasil mengambil data"),
            new OA\Response(response: 401, description: "Unauthenticated")
        ]
    )]
    public function regions(): \Illuminate\Http\JsonResponse
    {
        return $this->success($this->service->all());
    }

    #[OA\Get(
        path: "/regions-paginated",
        operationId: "getRegionPaginated",
        tags: ["Regions"],
        summary: "Daftar wilayah (Paginated)",
        description: "Mengambil daftar wilayah dengan sistem pagination",
        security: [["bearerAuth" => []]],
        parameters: [
            new OA\Parameter(name: "limit", in: "query", description: "Jumlah data per halaman", required: false, schema: new OA\Schema(type: "integer", example: 10))
        ],
        responses: [
            new OA\Response(response: 200, description: "Berhasil mengambil data"),
            new OA\Response(response: 401, description: "Unauthenticated")
        ]
    )]
    public function regionPaginated(Request $request): \Illuminate\Http\JsonResponse
    {
        return $this->success($this->service->paginate($request->limit));
    }

    #[OA\Post(
        path: "/regions",
        operationId: "storeRegion",
        tags: ["Regions"],
        summary: "Buat wilayah baru",
        description: "Menambahkan wilayah baru ke dalam database",
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["name"],
                properties: [
                    new OA\Property(property: "name", type: "string", example: "Jakarta Selatan")
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: "Wilayah berhasil dibuat"),
            new OA\Response(response: 422, description: "Validasi gagal"),
            new OA\Response(response: 401, description: "Unauthenticated")
        ]
    )]
    public function regionStore(Request $request)
    {
        try {
            $product = $this->service->create($request->all());
            return $this->success($product, "Region created", 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 422);
        }
    }

    #[OA\Delete(
        path: "/regions/{id}",
        operationId: "destroyRegion",
        tags: ["Regions"],
        summary: "Hapus wilayah",
        description: "Menghapus wilayah berdasarkan ID",
        security: [["bearerAuth" => []]],
        parameters: [
            new OA\Parameter(name: "id", in: "path", description: "ID Wilayah", required: true, schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(response: 200, description: "Wilayah berhasil dihapus"),
            new OA\Response(response: 401, description: "Unauthenticated")
        ]
    )]
    public function regionDestroy($id)
    {
        return $this->service->destroy($id);
    }

    #[OA\Put(
        path: "/regions/{id}",
        operationId: "updateRegion",
        tags: ["Regions"],
        summary: "Update wilayah",
        description: "Memperbarui data wilayah berdasarkan ID",
        security: [["bearerAuth" => []]],
        parameters: [
            new OA\Parameter(name: "id", in: "path", description: "ID Wilayah", required: true, schema: new OA\Schema(type: "integer"))
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "name", type: "string", example: "Jakarta Pusat")
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: "Wilayah berhasil diperbarui"),
            new OA\Response(response: 401, description: "Unauthenticated")
        ]
    )]
    public function regionUpdate(Request $request, $id)
    {
        return $this->service->update($request->all() , $id);
    }
}
