<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends BaseController
{
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

    public function me(Request $request)
    {
        return $this->success($request->user());

    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->success();
    }
}
