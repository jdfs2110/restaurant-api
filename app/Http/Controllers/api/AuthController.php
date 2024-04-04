<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UsuarioResource;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __construct(
        public readonly UserRepository $repository
    )
    {
    }

    public function register(Request $request): JsonResponse
    {
        $userData = $request->validate([
           'name' => 'required|string',
           'email' => 'required|string|unique:users,email',
           'password' => 'required|string|confirmed',
           'id_rol' =>  'required|int'
        ]);

        $user = $this->repository->create([
           'name' => $userData['name'],
           'email' => $userData['email'],
           'estado' => true,
           'password' => bcrypt($userData['password']),
           'fecha_ingreso' => date('Y-m-d'),
           'id_rol' => $userData['id_rol']
        ]);

        $token = $user->createToken('apiToken')->plainTextToken;

        $response = [
            'data' =>  new UsuarioResource($user),
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

        $user = $this->repository->findByEmail($userData['email']);

        if(is_null($user) || !Hash::check($userData['password'], $user->password)) {
            $loginError = [
                'error' => 'Usuario o contraseña incorrectos.'
            ];
            return response()->json($loginError, 400);
        }

        $token = $user->createToken('apiToken')->plainTextToken;

        $response = [
          'data' => new UsuarioResource($user),
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
