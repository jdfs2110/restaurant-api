<?php

namespace App\Http\Controllers\api;

use App\Exceptions\ModelNotFoundException;
use App\Exceptions\NoContentException;
use App\Http\Controllers\Controller;
use App\Http\Resources\PedidoResource;
use App\Http\Resources\UsuarioResource;
use App\Repositories\PedidoRepository;
use App\Repositories\RoleRepository;
use App\Repositories\UserRepository;
use App\Services\UserService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function __construct(
        public readonly UserRepository   $repository,
        public readonly UserService      $service,
        public readonly PedidoRepository $pedidoRepository,
        public readonly RoleRepository   $roleRepository
    )
    {
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $pagina = $request->query('page', 1);

            $users = $this->service->paginated($pagina);

            return $this->successResponse(UsuarioResource::collection($users), "Usuarios de la página $pagina");

        } catch (NoContentException $e) {
            return $this->errorResponse($e->getMessage(), 204);
        }
    }

    public function getAmountOfpages(): JsonResponse
    {
        $paginas = $this->service->getAmountOfpages();

        return $this->successResponse($paginas);
    }

    public function getUser($id): JsonResponse
    {
        try {
            $user = $this->repository->findOrFail($id);

            return $this->successResponse(new UsuarioResource($user));
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage());
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    public function getUsersPedidos($id): JsonResponse
    {
        try {
            $this->repository->findOrFail($id);

            $pedidos = $this->pedidoRepository->findPedidosByIdUsuario($id);

            return $this->successResponse(PedidoResource::collection($pedidos));
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage());
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }

    }

    public function getAllUsersByRole($id): JsonResponse
    {
        try {
            $this->roleRepository->findOrFail($id);

            $users = $this->service->findAllByIdRol($id);

            return $this->successResponse(UsuarioResource::collection($users));
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage());

        } catch (NoContentException $e) {
            return $this->errorResponse($e->getMessage(), 204);

        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function updateUser(Request $request, $id): JsonResponse
    {
        try {
            $data = $request->validate([
                'name' => 'required|string',
                'email' => 'required|string|unique:users,email',
                'password' => 'required|string|confirmed',
                'id_rol' => 'required|int'
            ]);

            $user = $this->repository->findOrFail($id);

            $update = $user->update([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
                'id_rol' => $data['id_rol']
            ]);
            $message = $update == 1 ? 'El usuario ha sido modificado correctamente.' : 'Error al modificar el usuario';

            return $this->successResponse(new UsuarioResource($user), $message);
        } catch (ValidationException $e) {
            return $this->errorResponse($e->errors(), 400);
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage());
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }
}
