<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PedidoResource;
use App\Http\Resources\UsuarioResource;
use App\Models\Role;
use App\Models\User;
use App\Models\Pedido;
use App\Repositories\UserRepository;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(
        public readonly UserRepository $repository
    )
    {
    }

    public function index(): JsonResponse
    {
        $users = $this->repository->all();

        return $this->successResponse(UsuarioResource::collection($users));
    }

    public function getUser($id): JsonResponse
    {
        try {
        $user = $this->repository->findOrFail($id);

        return $this->successResponse(new UsuarioResource($user));
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }

    }

    public function getUsersPedidos($id): JsonResponse
    {
        $user = User::query()->find($id);

        if (is_null($user)) {
            return $this->errorResponse('El usuario no existe');
        }

        $pedidos = Pedido::query()->where('id_usuario', $id)->get();

        return $this->successResponse(PedidoResource::collection($pedidos));
    }

    public function getAllUsersByRole($id): JsonResponse
    {
        $role = Role::query()->find($id);

        if (is_null($role)) {
            return $this->errorResponse('El rol no existe.');
        }

        $users = User::query()->where('id_rol', $id)->get();

        return $this->successResponse(UsuarioResource::collection($users));
    }

    public function updateUser(Request $request, $id): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed',
            'id_rol' =>  'required|int'
        ]);

        $user = User::query()->find($id);

        if (is_null($user)) {
            return $this->errorResponse('El usuario no existe.');
        }

        $update = $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'id_rol' => $data['id_rol']
        ]);
        $message = $update == 1 ? 'El usuario ha sido modificado correctamente.' : 'Error al modificar el usuario';

        return $this->successResponse(new UsuarioResource($user), $message);
    }
}
