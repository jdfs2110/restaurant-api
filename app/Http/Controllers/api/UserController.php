<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PedidoResource;
use App\Http\Resources\UsuarioResource;
use App\Models\User;
use App\Models\Pedido;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
class UserController extends Controller
{
    public function index(): JsonResponse
    {
        $users = User::all();

        $response = UsuarioResource::collection($users);

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

        $response = new UsuarioResource($user);

        return response()->json($response);
    }

    public function getUsersPedidos($id): JsonResponse
    {
        $pedidos = Pedido::query()->where('id_usuario', $id)->get();

        $response = PedidoResource::collection($pedidos);

        return response()->json($response);
    }
}
