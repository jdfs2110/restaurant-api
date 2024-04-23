<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UsuarioResource;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Services\UserService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Resend\Laravel\Facades\Resend;

class AuthController extends Controller
{
    public function __construct(
        public readonly UserRepository $repository,
        public readonly UserService $userService
    )
    {
    }

    public function register(Request $request): JsonResponse
    {

        try {
            $userData = $request->validate([
                'name' => 'required|string',
                'email' => 'required|string|unique:users,email',
                'password' => 'required|string|confirmed',
                'id_rol' => 'required|int'
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

            $this->userService->sendSuccessRegisterEmail($user);

            $response = [
                'data' => new UsuarioResource($user),
                'token' => $token
            ];

            return response()->json($response, 201);

        } catch (ValidationException $e) {
            return $this->errorResponse($e->errors(), 400);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    public function login(Request $request): JsonResponse
    {
        try {
            $userData = $request->validate([
                'email' => 'required|string',
                'password' => 'required|string'
            ]);

            $user = $this->repository->findByEmail($userData['email']);

            $this->userService->checkEmailAndPassword($user, $userData['password']);

            $token = $user->createToken('apiToken')->plainTextToken;

            $response = [
                'data' => new UsuarioResource($user),
                'token' => $token
            ];

            return response()->json($response);
        } catch (ValidationException $e) {
            return $this->errorResponse($e->errors(), 400);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
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
