<?php

namespace App\Http\Controllers\Api;

use App\Services\RentTransactionService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class RentTransactionController extends BaseController
{
    use ApiResponse;

    public function __construct(
        protected RentTransactionService $service
    )
    {
    }

    #[OA\Get(
        path: "/rent-transactions",
        operationId: "getRentTransactionList",
        tags: ["Transactions"],
        summary: "Daftar transaksi sewa",
        description: "Mengambil semua riwayat transaksi sewa barang",
        security: [["bearerAuth" => []]],
        responses: [
            new OA\Response(response: 200, description: "Berhasil mengambil data"),
            new OA\Response(response: 401, description: "Unauthenticated")
        ]
    )]
    public function rentTransactions(): \Illuminate\Http\JsonResponse
    {
        return $this->success($this->service->all());
    }

    #[OA\Get(
        path: "/rent-transactions-paginated",
        operationId: "getRentTransactionPaginated",
        tags: ["Transactions"],
        summary: "Daftar transaksi sewa (Paginated)",
        description: "Mengambil daftar transaksi dengan sistem pagination",
        security: [["bearerAuth" => []]],
        parameters: [
            new OA\Parameter(name: "limit", in: "query", description: "Jumlah data per halaman", required: false, schema: new OA\Schema(type: "integer", example: 10))
        ],
        responses: [
            new OA\Response(response: 200, description: "Berhasil mengambil data"),
            new OA\Response(response: 401, description: "Unauthenticated")
        ]
    )]
    public function rentTransactionPaginated(Request $request): \Illuminate\Http\JsonResponse
    {
        return $this->success($this->service->paginate($request->limit));
    }

    #[OA\Post(
        path: "/rent-transactions",
        operationId: "storeRentTransaction",
        tags: ["Transactions"],
        summary: "Buat transaksi sewa baru",
        description: "Mendaftarkan transaksi sewa barang baru dengan QR Code",
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["qr_uuid", "region_id", "renter_name", "renter_phone", "rent_date", "expected_return_date", "qty", "rent_price"],
                properties: [
                    new OA\Property(property: "qr_uuid", type: "string", example: "uuid-qr-123"),
                    new OA\Property(property: "region_id", type: "integer", example: 1),
                    new OA\Property(property: "renter_name", type: "string", example: "Budi Utomo"),
                    new OA\Property(property: "renter_phone", type: "string", example: "08123456789"),
                    new OA\Property(property: "rent_date", type: "string", format: "date", example: "2024-04-02"),
                    new OA\Property(property: "expected_return_date", type: "string", format: "date", example: "2024-04-05"),
                    new OA\Property(property: "qty", type: "integer", example: 1),
                    new OA\Property(property: "rent_price", type: "integer", example: 100000)
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: "Transaksi berhasil dibuat"),
            new OA\Response(response: 422, description: "Validasi gagal"),
            new OA\Response(response: 401, description: "Unauthenticated")
        ]
    )]
    public function rentTransactionStore(Request $request)
    {
        try {
            $data = $request->validate([
                'qr_uuid' => 'required|string',
                'region_id' => 'required|integer',
                'renter_name' => 'required|string',
                'renter_phone' => 'required|string',
                'rent_date' => 'required|date',
                'expected_return_date' => 'required|date',
                'qty' => 'required|integer|min:1',
                'rent_price' => 'required|integer',
            ]);

            $transaction = $this->service->createByQr($data);
            return $this->success($transaction, "Transaction created", 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 422);
        }
    }

    #[OA\Delete(
        path: "/rent-transactions/{id}",
        operationId: "destroyRentTransaction",
        tags: ["Transactions"],
        summary: "Hapus transaksi",
        description: "Menghapus riwayat transaksi sewa berdasarkan ID",
        security: [["bearerAuth" => []]],
        parameters: [
            new OA\Parameter(name: "id", in: "path", description: "ID Transaksi", required: true, schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(response: 200, description: "Transaksi berhasil dihapus"),
            new OA\Response(response: 401, description: "Unauthenticated")
        ]
    )]
    public function rentTransactionDestroy($id)
    {
        return $this->service->destroy($id);
    }

    #[OA\Put(
        path: "/rent-transactions/{id}",
        operationId: "updateRentTransaction",
        tags: ["Transactions"],
        summary: "Update transaksi",
        description: "Memperbarui data transaksi sewa berdasarkan ID",
        security: [["bearerAuth" => []]],
        parameters: [
            new OA\Parameter(name: "id", in: "path", description: "ID Transaksi", required: true, schema: new OA\Schema(type: "integer"))
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "renter_name", type: "string", example: "Budi Utomo Updated")
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: "Transaksi berhasil diperbarui"),
            new OA\Response(response: 401, description: "Unauthenticated")
        ]
    )]
    public function rentTransactionUpdate(Request $request, $id)
    {
        return $this->service->update($request->all(), $id);
    }
}
