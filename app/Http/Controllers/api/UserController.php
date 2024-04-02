<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PedidoResource;
use App\Http\Resources\UsuarioResource;
use App\Models\Role;
use App\Models\User;
use App\Models\Pedido;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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

        $response = [
            'pedidos' => PedidoResource::collection($pedidos)
        ];


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

    public function updateUser(Request $request, $id): JsonResponse
    {
        $userData = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed',
            'id_rol' =>  'required|int'
        ]);

        $user = User::query()->where('id', $id)->get()->first();

        if (is_null($user)) {
            $errorMessage = [
                'error' => 'El usuario no existe.'
            ];

            return response()->json($errorMessage, 404);
        }

        $update = User::query()->where('id', $id)->update([
            'name' => $userData['name'],
            'email' => $userData['email'],
            'password' => bcrypt($userData['password']),
            'id_rol' => $userData['id_rol']
        ]);
        $message = $update == 1 ? 'El usuario ha sido modificado correctamente.' : 'Error al modificar el usuario';

        $updatedUser = User::query()->where('id', $id)->get()->first();

        $response = [
            'usuario' => new UsuarioResource($updatedUser),
            'message' => $message
        ];

        return response()->json($response);
    }
}
