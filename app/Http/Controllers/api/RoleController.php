<?php

namespace App\Http\Controllers\api;

use App\Exceptions\ModelNotFoundException;
use App\Exceptions\NoContentException;
use App\Http\Controllers\Controller;
use App\Http\Resources\RoleResource;
use App\Models\Role;
use App\Repositories\RoleRepository;
use App\Repositories\UserRepository;
use App\Services\RoleService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class RoleController extends Controller
{
    public function __construct(
        public readonly RoleRepository $repository,
        public readonly RoleService    $service,
        public readonly UserRepository $userRepository
    )
    {
    }

    function index(): JsonResponse
    {
        try {
            $roles = $this->service->all();

            return $this->successResponse(RoleResource::collection($roles));

        } catch (NoContentException $e) {
            return $this->errorResponse($e->getMessage(), 204);
        }
    }

    function getRole($id): JsonResponse
    {
        try {
            $role = $this->repository->findOrFail($id);

            return $this->successResponse(new RoleResource($role));
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage());
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    function newRole(Request $request): JsonResponse
    {
        try {
            $data = $request->validate([
                'nombre' => 'required|string'
            ]);

            $role = $this->repository->create([
                'nombre' => $data['nombre']
            ]);

            return $this->successResponse(new RoleResource($role), 'Rol creado correctamente.', 201);
        } catch (ValidationException $e) {
            return $this->errorResponse($e->errors(), 400);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    public function deleteRole($id): JsonResponse
    {
        try {
            $role = $this->repository->findOrFail($id);
            $usersWithRole = $this->userRepository->findAllByIdRol($id);

            if ($usersWithRole->isNotEmpty()) {
                return $this->errorResponse('Error al eliminar el rol, el rol tiene usuarios', 400);
            }

            $deletion = $this->repository->delete($role);
            $message = $deletion == 1 ? 'El rol ha sido eliminado correctamente' : 'Error al eliminar el rol';

            return $this->successResponse('', $message);
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage());
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    public function updateRole(Request $request, $id): JsonResponse
    {
        try {
            $data = $request->validate([
                'nombre' => 'required|string'
            ]);

            $role = $this->repository->findOrFail($id);

            $role->setNombre($data['nombre']);

            $update = $role->save();
            $message = $update == 1 ? 'El rol ha sido modificado correctamente.' : 'Error al modificar el rol.';

            return $this->successResponse(new RoleResource($role), $message);
        } catch (ValidationException $e) {
            return $this->errorResponse($e->errors(), 400);
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage());
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }
}
