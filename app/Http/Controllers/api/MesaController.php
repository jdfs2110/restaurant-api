<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MesaResource;
use App\Models\Mesa;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MesaController extends Controller
{
    function index(): JsonResponse
    {
        $mesas = Mesa::all();

        $response =[
            'mesas' => MesaResource::collection($mesas)
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
            'mesa' => new MesaResource($mesa)
        ];

        return response()->json($response);
    }

    function newMesa(Request $request): JsonResponse
    {
        $mesaData = $request->validate([
            'capacidad_maxima' => 'int|required|max:10',
            'estado' => 'int|required|max:2'
        ]);

        $mesa = Mesa::query()->create([
           'capacidad_maxima' => $mesaData['capacidad_maxima'],
           'estado' => $mesaData['estado']
        ]);

        $response = new MesaResource($mesa);

        return response()->json($response);
    }
}
