<?php

namespace App\Http\Controllers\Api;

use App\Services\UserService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class UserController extends BaseController
{
    use ApiResponse;

    public function __construct(
        protected UserService $service
    ) {}

    #[OA\Get(
        path: "/users",
        operationId: "getUserList",
        tags: ["Users"],
        summary: "Mendapatkan daftar semua user",
        description: "Mengembalikan array berisi semua data user",
        security: [["bearerAuth" => []]],
        responses: [
            new OA\Response(response: 200, description: "Berhasil mengambil data"),
            new OA\Response(response: 401, description: "Unauthenticated")
        ]
    )]
    public function users(): \Illuminate\Http\JsonResponse
    {
        return $this->success($this->service->all());
    }

    #[OA\Get(
        path: "/user-paginated",
        operationId: "getUserPaginated",
        tags: ["Users"],
        summary: "Daftar user (Paginated)",
        description: "Mengambil daftar user dengan sistem pagination",
        security: [["bearerAuth" => []]],
        parameters: [
            new OA\Parameter(name: "limit", in: "query", description: "Jumlah data per halaman", required: false, schema: new OA\Schema(type: "integer", example: 10))
        ],
        responses: [
            new OA\Response(response: 200, description: "Berhasil mengambil data"),
            new OA\Response(response: 401, description: "Unauthenticated")
        ]
    )]
    public function userPaginated(Request $request): \Illuminate\Http\JsonResponse
    {
        return $this->success($this->service->paginate($request->limit));
    }

    #[OA\Post(
        path: "/user",
        operationId: "storeUser",
        tags: ["Users"],
        summary: "Menambahkan user baru",
        description: "Membuat data user baru ke dalam database",
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["region_id", "name", "email", "password", "role"],
                properties: [
                    new OA\Property(property: "region_id", type: "integer", example: 1),
                    new OA\Property(property: "name", type: "string", example: "Staff A"),
                    new OA\Property(property: "email", type: "string", example: "staffA@example.com"),
                    new OA\Property(property: "password", type: "string", example: "password123"),
                    new OA\Property(property: "role", type: "string", example: "staff"),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: "User berhasil dibuat"),
            new OA\Response(response: 422, description: "Validasi gagal")
        ]
    )]
    public function userStore(Request $request)
    {
        try {
            $data = $request->validate([
                'region_id' => 'required|integer|exists:regions,id',
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:6',
                'role' => 'required|string|in:admin,staff,user',
            ]);
            $user = $this->service->create($data);
            return $this->success($user, "User created", 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->error("Validasi gagal", 422, $e->errors());
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 422);
        }
    }

    #[OA\Delete(
        path: "/user/{id}",
        operationId: "destroyUser",
        tags: ["Users"],
        summary: "Hapus user",
        description: "Menghapus user berdasarkan ID",
        security: [["bearerAuth" => []]],
        parameters: [
            new OA\Parameter(name: "id", in: "path", description: "ID User", required: true, schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(response: 200, description: "User berhasil dihapus"),
            new OA\Response(response: 401, description: "Unauthenticated")
        ]
    )]
    public function userDestroy($id)
    {
        try {
            $this->service->destroy($id);
            return $this->success(null, "User deleted");
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 422);
        }
    }

    #[OA\Put(
        path: "/user/{id}",
        operationId: "updateUser",
        tags: ["Users"],
        summary: "Update user",
        description: "Memperbarui data user berdasarkan ID",
        security: [["bearerAuth" => []]],
        parameters: [
            new OA\Parameter(name: "id", in: "path", description: "ID User", required: true, schema: new OA\Schema(type: "integer"))
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "region_id", type: "integer", example: 1),
                    new OA\Property(property: "name", type: "string", example: "Staff A"),
                    new OA\Property(property: "email", type: "string", example: "staffA@example.com"),
                    new OA\Property(property: "password", type: "string", example: "password123"),
                    new OA\Property(property: "role", type: "string", example: "staff"),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: "User berhasil diperbarui"),
            new OA\Response(response: 401, description: "Unauthenticated")
        ]
    )]
    public function userUpdate(Request $request, $id)
    {
        try {
            $data = $request->validate([
                'region_id' => 'sometimes|integer|exists:regions,id',
                'name' => 'sometimes|string|max:255',
                'email' => 'sometimes|email|unique:users,email,' . $id,
                'password' => 'sometimes|nullable|string|min:6',
                'role' => 'sometimes|string|in:admin,staff,user',
            ]);
            $updated = $this->service->update($data, $id);
            return $this->success($updated, "User updated");
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->error("Validasi gagal", 422, $e->errors());
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 422);
        }
    }
}
