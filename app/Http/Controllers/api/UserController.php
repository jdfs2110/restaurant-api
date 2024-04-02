<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PedidoResource;
use App\Http\Resources\UsuarioResource;
use App\Models\Role;
use App\Models\User;
use App\Models\Pedido;
use Illuminate\Http\JsonResponse;
class UserController extends Controller
{
    public function index(): JsonResponse
    {
        $users = User::all();

        $response = [
            'usuarios' => UsuarioResource::collection($users)
        ];
        return response()->json($response);
    }

    public function getUser($id): JsonResponse
    {
        $user = User::query()->where('id', $id)->get()->first();

        if (is_null($user)) {
            $errorMessage = [
                'error' => 'El usuario no existe.'
            ];

            return response()->json($errorMessage, 404);
        }

        $response = [
            'usuario' => new UsuarioResource($user)
        ];
        return response()->json($response);
    }

    public function getUsersPedidos($id): JsonResponse
    {
        $pedidos = Pedido::query()->where('id_usuario', $id)->get();

        $response = PedidoResource::collection($pedidos);

        return response()->json($response);
    }

    public function getAllUsersByRole($id): JsonResponse
    {
        $role = Role::query()->where('id', $id)->get()->first();

        if (is_null($role)) {
            $errorMessage = [
                'error' => 'El rol no existe.'
            ];

            return response()->json($errorMessage, 404);
        }

        $users = User::query()->where('id_rol', $id)->get();

        if (is_null($users)) {
            $errorMessage = [
                'error' => 'No hay usuarios con este rol.'
            ];

            return response()->json($errorMessage, 404);
        }

        $response = [
            'usuarios' => $users
        ];

        return response()->json($response);
    }
}
