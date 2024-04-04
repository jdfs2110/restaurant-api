<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PedidoResource;
use App\Http\Resources\UsuarioResource;
use App\Models\Role;
use App\Models\User;
use App\Models\Pedido;
use App\Repositories\PedidoRepository;
use App\Repositories\RoleRepository;
use App\Repositories\UserRepository;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(
        public readonly UserRepository   $repository,
        public readonly PedidoRepository $pedidoRepository,
        public readonly RoleRepository   $roleRepository
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
        try {
            $this->repository->findOrFail($id);
            $pedidos = $this->pedidoRepository->findPedidosByIdUsuario($id);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }

        return $this->successResponse(PedidoResource::collection($pedidos));
    }

    public function getAllUsersByRole($id): JsonResponse
    {
        try {
            $this->roleRepository->findOrFail($id);
            $users = $this->repository->findAllByIdRol($id);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }

        return $this->successResponse(UsuarioResource::collection($users));
    }

    public function updateUser(Request $request, $id): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed',
            'id_rol' => 'required|int'
        ]);

        try {
            $user = $this->repository->findOrFail($id);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
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
