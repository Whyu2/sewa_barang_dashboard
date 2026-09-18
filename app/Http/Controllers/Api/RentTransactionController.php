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
    public function rentTransactions(Request $request): \Illuminate\Http\JsonResponse
    {
        return $this->success($this->service->allFiltered(
            $request->region_id,
            $this->service->resolveCreatedBy($request->mine, $request->created_by),
        ));
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
        return $this->success($this->service->paginateFiltered(
            $request->limit,
            $request->region_id,
            $this->service->resolveCreatedBy($request->mine, $request->created_by),
        ));
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
                required: ["qr_uuid", "region_id", "renter_name", "renter_phone", "rent_date", "qty", "rent_price"],
                properties: [
                    new OA\Property(property: "qr_uuid", type: "string", example: "uuid-qr-123"),
                    new OA\Property(property: "region_id", type: "integer", example: 1),
                    new OA\Property(property: "renter_name", type: "string", example: "Budi Utomo"),
                    new OA\Property(property: "renter_phone", type: "string", example: "08123456789"),
                    new OA\Property(property: "rent_date", type: "string", format: "date", example: "2024-04-02"),
                    new OA\Property(property: "rental_duration_days", type: "integer", example: 3, description: "Lama sewa dalam hari. expected_return_date = rent_date + durasi"),
                    new OA\Property(property: "expected_return_date", type: "string", format: "date", example: "2024-04-05", description: "Opsional jika rental_duration_days diisi (akan dihitung otomatis)"),
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
                'renter_phone' => 'nullable|string|max:50',
                'rent_date' => 'required|date',
                'rental_duration_days' => 'required_without:expected_return_date|nullable|integer|min:1',
                'expected_return_date' => 'required_without:rental_duration_days|nullable|date|after_or_equal:rent_date',
                'qty' => 'required|integer|min:1',
                'rent_price' => 'required|integer',
                'notes' => 'nullable|string',
                'pickup_proof' => 'nullable|image|max:4096',
            ]);
            // Durasi jadi sumber utama: expected = rent_date + N hari (tanpa kolom baru)
            if (!empty($data['rental_duration_days'])) {
                $data['expected_return_date'] = \Carbon\Carbon::parse($data['rent_date'])->addDays((int) $data['rental_duration_days'])->toDateString();
            }
            unset($data['rental_duration_days']);
            if ($request->hasFile('pickup_proof')) {
                $file = $request->file('pickup_proof');
                $path = 'pickup_proofs/' . $file->hashName();
                \Illuminate\Support\Facades\Storage::disk('public')->put($path, file_get_contents($file));
                $data['pickup_proof_url'] = asset('storage/' . $path);
            }

            $transaction = $this->service->createByQr($data);
            return $this->success($transaction, "Transaction created", 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 422);
        }
    }

    #[OA\Get(path: "/rent-transaction/{id}", operationId: "getRentTransactionShow", tags: ["Transactions"], summary: "Detail transaksi", security: [["bearerAuth"=>[]]], parameters: [new OA\Parameter(name:"id", in:"path", required:true, schema: new OA\Schema(type:"integer"))], responses: [new OA\Response(response:200, description:"Berhasil"), new OA\Response(response:404, description:"Not found")])]
    public function rentTransactionShow($id): \Illuminate\Http\JsonResponse
    {
        $tx = $this->service->find($id);
        if (!$tx) return $this->error("Transaction not found", 404);
        return $this->success($tx);
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
        $this->service->destroy($id);
        return $this->success(null, "Transaction deleted");
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
        $data = $request->validate([
            'renter_name' => 'sometimes|string|max:255',
            'renter_phone' => 'sometimes|string|max:50',
            'rent_date' => 'sometimes|date',
            'rental_duration_days' => 'sometimes|nullable|integer|min:1',
            'expected_return_date' => 'sometimes|date|after_or_equal:rent_date',
            'return_date' => 'required_if:status,returned|nullable|date|after_or_equal:rent_date',
            'qty' => 'sometimes|integer|min:1',
            'rent_price' => 'sometimes|integer|min:0',
            'status' => 'sometimes|string|in:rented,returned,overdue',
            'notes' => 'sometimes|nullable|string',
            'region_id' => 'sometimes|integer|exists:regions,id',
            'return_proof' => 'sometimes|nullable|image|max:4096',
            'remove_return_proof' => 'sometimes|boolean',
        ]);

        if ($request->hasFile('return_proof')) {
            // Hapus file lama agar tidak menumpuk di storage
            $this->deleteProofFile(optional($this->service->find($id))->return_proof_url);
            $file = $request->file('return_proof');
            $path = 'return_proofs/' . $file->hashName();
            \Illuminate\Support\Facades\Storage::disk('public')->put($path, file_get_contents($file));
            $data['return_proof_url'] = asset('storage/' . $path);
        } elseif ($request->boolean('remove_return_proof')) {
            $this->deleteProofFile(optional($this->service->find($id))->return_proof_url);
            $data['return_proof_url'] = null;
        }
        unset($data['remove_return_proof']);

        // Jika durasi dikirim (dari form dashboard), hitung ulang expected agar konsisten
        if (!empty($data['rental_duration_days'])) {
            $baseRentDate = $data['rent_date'] ?? optional($this->service->find($id))->rent_date;
            if ($baseRentDate) {
                $data['expected_return_date'] = \Carbon\Carbon::parse($baseRentDate)->addDays((int) $data['rental_duration_days'])->toDateString();
            }
        }
        unset($data['rental_duration_days']);

        $updated = $this->service->update($data, $id);
        return $this->success($updated, "Transaction updated");
    }

    private function deleteProofFile(?string $url): void
    {
        if (!$url) return;
        // URL tersimpan sebagai asset('storage/...') -> ambil path relatif terhadap disk public
        $marker = '/storage/';
        $pos = strpos($url, $marker);
        if ($pos === false) return;
        $relative = substr($url, $pos + strlen($marker));
        if ($relative) \Illuminate\Support\Facades\Storage::disk('public')->delete($relative);
    }
}
