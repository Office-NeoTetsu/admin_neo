<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
    public function index()
    {
        return UserResource::collection(User::all());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:user',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'username'     => $request->username,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return new UserResource($user, true, 'User berhasil dibuat!');
    }


    public function show($id)
    {
        $user = User::find($id);

        return new UserResource($user, true, 'Detail Data Product!');
    }


    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'username'     => 'nullable|string|max:255',
            'email'    => 'nullable|string|email|max:255|unique:user,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::find($id);

        $user->update([
            'username' => $request->username ?? $user->username,
            'email' => $request->email ?? $user->email,
            'password' => $request->filled('password')
                ? Hash::make($request->password)
                : $user->password,
        ]);

        return new UserResource(true, 'Data User Berhasil Diubah!', $user);
    }

    public function destroy($id)
    {
        $user = User::find($id);

        $user->delete();

        return new UserResource(true, 'Data User Berhasil Dihapus', null);
    }
}
