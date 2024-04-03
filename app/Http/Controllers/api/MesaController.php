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

        return $this->successResponse(MesaResource::collection($mesas));
    }

    function getMesa($id): JsonResponse
    {
        $mesa = Mesa::query()->find($id);

        if (is_null($mesa)) {
            return $this->errorResponse('La mesa no existe.');
        }

        return $this->successResponse(new MesaResource($mesa));
    }

    function newMesa(Request $request): JsonResponse
    {
        $data = $request->validate([
            'capacidad_maxima' => 'required|int|max:10',
            'estado' => 'required|int|max:2'
        ]);

        $mesa = Mesa::query()->create([
            'capacidad_maxima' => $data['capacidad_maxima'],
            'estado' => $data['estado']
        ]);

        return $this->successResponse(new MesaResource($mesa));
    }

    function deleteMesa($id): JsonResponse
    {
        $mesa = Mesa::query()->find($id);

        if (is_null($mesa)) {
            return $this->errorResponse('La mesa no existe.');
        }

        $deletion = $mesa->delete();
        $message = $deletion == 1 ? 'La mesa ha sido eliminada correctamente' : 'Error al eliminar la mesa';

        return $this->successResponse('', $message);
    }

    function updateMesa(Request $request, $id): JsonResponse
    {
        $data = $request->validate([
            'capacidad_maxima' => 'required|int|max:10',
            'estado' => 'required|int|max:2'
        ]);

        $mesa = Mesa::query()->find($id);

        if (is_null($mesa)) {
            return $this->errorResponse('La mesa no existe.');
        }

        $update = $mesa->update([
            'capacidad_maxima' => $data['capacidad_maxima'],
            'estado' => $data['estado']
        ]);
        $message = $update == 1 ? 'La mesa ha sido modificada correctamente.' : 'Error al modificar la mesa.';

        return $this->successResponse(new MesaResource($mesa), $message);
    }
}
