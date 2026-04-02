<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use OpenApi\Attributes as OA;

class AuthController extends BaseController
{
    #[OA\Post(
        path: "/login",
        operationId: "authLogin",
        tags: ["Auth"],
        summary: "User login",
        description: "Login dengan email dan password untuk mendapatkan Bearer Token",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["email", "password"],
                properties: [
                    new OA\Property(property: "email", type: "string", format: "email", example: "admin@example.com"),
                    new OA\Property(property: "password", type: "string", format: "password", example: "password")
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: "Login Berhasil"),
            new OA\Response(response: 401, description: "Email atau Password salah")
        ]
    )]
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Email atau password salah'
            ], 401);
        }

        // hapus token lama (optional)
        $user->tokens()->delete();

        return $this->success([
            'token' => $user->createToken('api')->plainTextToken,
            'user' => $user
        ]);
    }

    #[OA\Get(
        path: "/me",
        operationId: "authMe",
        tags: ["Auth"],
        summary: "Get current user profile",
        description: "Mengambil data user yang sedang login menggunakan token",
        security: [["bearerAuth" => []]],
        responses: [
            new OA\Response(response: 200, description: "Berhasil mengambil data profile"),
            new OA\Response(response: 401, description: "Unauthenticated")
        ]
    )]
    public function me(Request $request)
    {
        return $this->success($request->user());

    }

    #[OA\Post(
        path: "/logout",
        operationId: "authLogout",
        tags: ["Auth"],
        summary: "Logout user",
        description: "Menghapus token akses yang sedang digunakan",
        security: [["bearerAuth" => []]],
        responses: [
            new OA\Response(response: 200, description: "Logout Berhasil"),
            new OA\Response(response: 401, description: "Unauthenticated")
        ]
    )]
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->success();
    }
}
