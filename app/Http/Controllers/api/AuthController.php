<?php

namespace App\Http\Controllers\api;

use App\Exceptions\IncorrectLoginException;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Resources\UsuarioResource;
use App\Services\UserService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\PersonalAccessToken;
use Resend\Exceptions\ErrorException;

class AuthController extends Controller
{
    public function __construct(
        public readonly UserRepository $repository,
        public readonly UserService    $userService
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

            $this->userService->sendSuccessRegisterEmail($user);

            return $this->successResponse(new UsuarioResource($user), 'Registro exitoso.', 201);

        } catch (ValidationException $e) {
            return $this->errorResponse($e->errors(), 400);

        } catch (ErrorException) {
            return $this->successResponse(new UsuarioResource($user), 'Registro exitoso.', 201);

        } catch (Exception) {
            return $this->unhandledErrorResponse();
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

        } catch (IncorrectLoginException $e) {
            return $this->errorResponse($e->getMessage(), 400);

        } catch (Exception) {
            return $this->unhandledErrorResponse();
        }
    }

    public function logout(Request $request): JsonResponse
    {
        try {
            PersonalAccessToken::findToken($request->bearerToken())->delete();

            return $this->successResponse('', 'logged out.');

        } catch (Exception) {
            return $this->unhandledErrorResponse();
        }
    }

    public function validateToken(Request $request): JsonResponse
    {
        try {
            return $this->successResponse(new UsuarioResource($request->user()));

        } catch (Exception) {
            return $this->unhandledErrorResponse();
        }
    }
}
