<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Roles;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    function index(): JsonResponse
    {
        $roles = Roles::all();

        $response = [
            'roles' => $roles
        ];

        return response()->json($response);
    }

    function getRole($id): JsonResponse
    {
        $role = Roles::query()->where('id', $id)->get()->first();

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
}
