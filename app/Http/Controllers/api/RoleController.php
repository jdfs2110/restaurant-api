<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    function index(): JsonResponse
    {
        $roles = Role::all();

        $response = [
            'roles' => $roles
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
            'rol' => $role
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
            'rol' => $rol
        ];

        return response()->json($response);
    }
}
