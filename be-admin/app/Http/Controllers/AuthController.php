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

        // Jika user punya pin, kirimkan pin_token (token sementara)
        if (!empty($user->pin)) {
            $pinToken = $user->createToken('pin_token')->plainTextToken;
            return response()->json([
                'message' => 'Kredensial valid, silahkan masukkan PIN.',
                'pin_token' => $pinToken,
                'token_type' => 'Bearer'
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

    // Endpoint verifikasi pin
    public function verifyPin(Request $request)
    {
        $request->validate([
            'pin' => 'required|string',
        ]);

        // Ambil user dari token sementara (pin_token)
        $user = $request->user();
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
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ]);
    }

}
