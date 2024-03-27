<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $userData = $request->validate([
           'name' => 'required|string',
           'email' => 'required|string|unique:users,email',
           'password' => 'required|string|confirmed',
           'id_rol' =>  'required|int'
        ]);

        $user = User::query()->create([
           'name' => $userData['name'],
           'email' => $userData['email'],
           'password' => bcrypt($userData['password']),
           'fecha_ingreso' => date('Y-m-d'),
           'id_rol' => $userData['id_rol']
        ]);

        $token = $user->createToken('apiToken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response()->json($response);
    }

    public function login(Request $request): JsonResponse
    {
        $userData = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        $user = User::query()->where('email', $userData['email'])->get()->first();

        if(is_null($user) || !Hash::check($userData['password'], $user->password)) {
            $loginError = [
                'error' => 'Usuario o contraseña incorrectos.'
            ];
            return response()->json($loginError, 400);
        }

        $token = $user->createToken('apiToken')->plainTextToken;

        $response = [
          'user' => $user,
          'token' => $token
        ];

        return response()->json($response);
    }

    public function logout(Request $request): JsonResponse
    {
        auth()->user()->tokens()->delete();

        $response = [
            'message' => 'logged out'
        ];

        return response()->json($response);
    }
}
