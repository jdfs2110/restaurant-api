<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
class UserController extends Controller
{
    public function index(): JsonResponse
    {
        $users = User::all();

        $response = [
            'users' => $users
        ];

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

        $response = [
            'user' => $user
        ];

        return response()->json($response);
    }
}
