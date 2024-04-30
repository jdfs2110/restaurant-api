<?php

namespace App\Http\Controllers\api;

use App\Exceptions\EmailAlreadyInUseException;
use App\Exceptions\ModelNotFoundException;
use App\Exceptions\NoContentException;
use App\Exceptions\UserIsNotWaiterException;
use App\Http\Controllers\Controller;
use App\Repositories\PedidoRepository;
use App\Repositories\RoleRepository;
use App\Repositories\UserRepository;
use App\Resources\PedidoResource;
use App\Resources\UsuarioResource;
use App\Services\PedidoService;
use App\Services\UserService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use TypeError;

class UserController extends Controller
{
    public function __construct(
        public readonly UserRepository   $repository,
        public readonly UserService      $service,
        public readonly PedidoRepository $pedidoRepository,
        public readonly PedidoService    $pedidoService,
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

        } catch (Exception $e) {
            return $this->unhandledErrorResponse($e->getMessage());
        }
    }

    public function getAmountOfpages(): JsonResponse
    {
        try {
            $paginas = $this->service->getAmountOfpages();

            return $this->successResponse($paginas);

        } catch (Exception $e) {
            return $this->unhandledErrorResponse($e->getMessage());
        }
    }

    public function getUser($id): JsonResponse
    {
        try {
            $user = $this->repository->findOrFail($id);

            return $this->successResponse(new UsuarioResource($user));

        } catch (TypeError) {
            return $this->errorResponse("Debes de introducir un número. (Valor introducido: $id)", 400);

        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage());

        } catch (Exception $e) {
            return $this->unhandledErrorResponse($e->getMessage());
        }
    }

    public function getUsersPedidos($id): JsonResponse
    {
        try {
            $pedidos = $this->pedidoService->findPedidosByIdUsuario($id);

            return $this->successResponse(PedidoResource::collection($pedidos));

        } catch (TypeError) {
            return $this->errorResponse("Debes de introducir un número. (Valor introducido: $id)", 400);

        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage());

        } catch (NoContentException $e) {
            return $this->errorResponse($e->getMessage(), 204);

        } catch (UserIsNotWaiterException $e) {
            return $this->errorResponse($e->getMessage(), 400);

        } catch (Exception $e) {
            return $this->unhandledErrorResponse($e->getMessage());
        }
    }

    public function getAllUsersByRole($id): JsonResponse
    {
        try {
            $this->roleRepository->findOrFail($id);

            $users = $this->service->findAllByIdRol($id);

            return $this->successResponse(UsuarioResource::collection($users));

        } catch (TypeError) {
            return $this->errorResponse("Debes de introducir un número. (Valor introducido: $id)", 400);

        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage());

        } catch (NoContentException $e) {
            return $this->errorResponse($e->getMessage(), 204);

        } catch (Exception $e) {
            return $this->unhandledErrorResponse($e->getMessage());
        }
    }

    public function updateUser(Request $request, $id): JsonResponse
    {
        try {
            $data = $request->validate([
                'name' => 'required|string',
                'email' => 'required|string',
                'password' => 'required|string|confirmed',
                'id_rol' => 'required|int'
            ]);

            $user = $this->repository->findOrFail($id);

            $this->repository->emailExists($id, $data["email"]);

            $update = $user->update([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
                'id_rol' => $data['id_rol']
            ]);
            $message = $update == 1 ? 'El usuario ha sido modificado correctamente.' : 'Error al modificar el usuario';

            $updatedUser = $this->repository->findOrFail($id);

            $this->service->sendUpdatedUserEmail($updatedUser);

            return $this->successResponse(new UsuarioResource($user), $message);

        } catch (TypeError) {
            return $this->errorResponse("Debes de introducir un número. (Valor introducido: $id)", 400);

        } catch (ValidationException $e) {
            return $this->errorResponse($e->errors(), 400);

        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage());

        } catch (EmailAlreadyInUseException $e) {
            return $this->errorResponse($e->getMessage(), 400);

        } catch (Exception $e) {
            return $this->unhandledErrorResponse($e->getMessage());
        }
    }

    public function deleteUser($id): JsonResponse
    {
        try {
            $user = $this->repository->findOrFail($id);

            $deletion = $this->repository->delete($user);
            $message = $deletion == '1' ? 'El usuario ha sido eliminado correctamente.' : 'Error al eliminar el usuario.';

            $this->service->sendGoodByeEmail($user);

            return $this->successResponse('', $message);

        } catch (TypeError) {
            return $this->errorResponse("Debes de introducir un número. (Valor introducido: $id)", 400);

        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage());

        } catch (Exception $e) {
            return $this->unhandledErrorResponse($e->getMessage());
        }
    }
}
