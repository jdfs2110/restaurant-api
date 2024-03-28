<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Mesa;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MesaController extends Controller
{
    function index(): JsonResponse
    {
        $mesas = Mesa::all();

        $response = [
            'mesas' => $mesas
        ];

        return response()->json($response);
    }

    function getMesa($id): JsonResponse
    {
        $mesa = Mesa::query()->where('id', $id)->get()->first();

        if (is_null($mesa)) {
            $errorMessage = [
                'error' => 'La mesa no existe.'
            ];

            return response()->json($errorMessage, 404);
        }

        $response = [
            'mesa' => $mesa
        ];

        return response()->json($response);
    }
}
