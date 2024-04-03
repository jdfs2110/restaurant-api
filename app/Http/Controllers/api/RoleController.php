<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RoleResource;
use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    function index(): JsonResponse
    {
        $roles = Role::all();

        $response = [
            'roles' => RoleResource::collection($roles)
        ];

        return response()->json($response);
    }

    function getRole($id): JsonResponse
    {
        $role = Role::query()->where('id', $id)->get()->first();

        if (is_null($role)) {
            $errorMessage = [
                'error' => 'El rol no existe.'
            ];

            return response()->json($errorMessage, 404);
        }

        $response = [
            'rol' => new RoleResource($role)
        ];

        return response()->json($response);
    }

    function newRole(Request $request): JsonResponse
    {
        $rolData = $request->validate([
           'nombre' => 'required|string'
        ]);

        $rol = Role::query()->create([
           'nombre' => $rolData['nombre']
        ]);

        $response = [
            'rol' => new RoleResource($rol)
        ];

        return response()->json($response);
    }

    public function deleteRole($id): JsonResponse
    {
        $role = Role::query()->find($id);

        if (is_null($role)) {
            $errorMessage = [
                'error' => 'El rol no existe.'
            ];

            return response()->json($errorMessage, 404);
        }

        $deletion = Role::query()->where('id', $id)->delete();
        $message = $deletion == 1 ? 'El rol ha sido eliminado correctamente' : 'Error al eliminar el rol';

        $response = [
            'message' => $message
        ];

        return response()->json($response);
    }

    public function updateRole(Request $request, $id): JsonResponse
    {
        $rolData = $request->validate([
            'nombre' => 'required|string'
        ]);

        $role = Role::query()->where('id', $id)->get()->first();

        if (is_null($role)) {
            $errorMessage = [
                'error' => 'El rol no existe.'
            ];

            return response()->json($errorMessage, 404);
        }

//        $role->nombre = $rolData['nombre'];

//        $role->save();

        $update = Role::query()->where('id', $id)->update([
            'nombre' => $rolData['nombre']
        ]);
        $message = $update == 1 ? 'El rol ha sido modificado correctamente.' : 'Error al modificar el rol.';

        $updatedRol = Role::query()->where('id', $id)->get()->first();

        $response = [
            'rol' => new RoleResource($updatedRol),
            'message' => $message
        ];

        return response()->json($response);
    }
}
