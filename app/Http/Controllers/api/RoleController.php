<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RoleResource;
use App\Models\Role;
use App\Repositories\RoleRepository;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function __construct(
        public readonly RoleRepository $repository
    )
    {
    }

    function index(): JsonResponse
    {
        $roles = $this->repository->all();

        return $this->successResponse(RoleResource::collection($roles));
    }

    function getRole($id): JsonResponse
    {
        try {

        $role = $this->repository->findOrFail($id);

        return $this->successResponse(new RoleResource($role));

        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    function newRole(Request $request): JsonResponse
    {
        $data = $request->validate([
           'nombre' => 'required|string'
        ]);

        $role = $this->repository->create([
           'nombre' => $data['nombre']
        ]);

        return $this->successResponse(new RoleResource($role));
    }

    public function deleteRole($id): JsonResponse
    {
        $role = Role::query()->find($id);

        if (is_null($role)) {
            return $this->errorResponse('El rol no existe.');
        }

        $deletion = $role->delete();
        $message = $deletion == 1 ? 'El rol ha sido eliminado correctamente' : 'Error al eliminar el rol';

        return $this->successResponse('', $message);
    }

    public function updateRole(Request $request, $id): JsonResponse
    {
        $data = $request->validate([
            'nombre' => 'required|string'
        ]);

        $role = Role::query()->find($id);

        if (is_null($role)) {
            return $this->errorResponse('El rol no existe.');
        }

        $role->nombre = $data['nombre'];

        $update = $role->save();
        $message = $update == 1 ? 'El rol ha sido modificado correctamente.' : 'Error al modificar el rol.';

        return $this->successResponse(new RoleResource($role), $message);
    }
}
