<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required',
            'password' => 'required',
        ]);

        $login_type = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $user = User::where($login_type, $request->login)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            $field = $login_type === 'email' ? 'Email' : 'Username';
            return response()->json(['error' => "$field atau password salah"], 401);
        }

        // Jika user punya pin, kirimkan temp_token (token sementara)
        if (!empty($user->pin)) {
            $tempToken = $user->createToken('temp_token')->plainTextToken;
            return response()->json([
                'message' => 'Masukkan PIN untuk melanjutkan.',
                'temp_token' => $tempToken
            ], 200);
        }

        // User tanpa pin, langsung login
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ]);
    }

    // Endpoint verifikasi pin dengan temp_token di body
    public function verifyPin(Request $request)
    {
        $request->validate([
            'temp_token' => 'required|string',
            'pin' => 'required|string',
        ]);

        // Ambil user dari temp_token
        $tokenValue = $request->temp_token;
        $tokenParts = explode('|', $tokenValue);
        if (count($tokenParts) !== 2) {
            return response()->json(['error' => 'PIN tidak valid'], 401);
        }
        $token = \Laravel\Sanctum\PersonalAccessToken::findToken($tokenValue);
        if (!$token) {
            return response()->json(['error' => 'PIN tidak valid'], 401);
        }
        $user = $token->tokenable;
        if (!$user) {
            return response()->json(['error' => 'User tidak ditemukan'], 404);
        }
        if (empty($user->pin)) {
            return response()->json(['error' => 'User tidak memiliki PIN'], 400);
        }
        if ($user->pin !== $request->pin) {
            return response()->json(['error' => 'PIN salah'], 401);
        }

        // Buat token final
        $finalToken = $user->createToken('auth_token')->plainTextToken;

        // Hapus temp_token setelah digunakan
        $token->delete();

        return response()->json([
            'token' => $finalToken,
            'token_type' => 'Bearer',
            'user' => $user,
        ]);
    }

}
