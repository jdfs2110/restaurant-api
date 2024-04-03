<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoriaResource;
use App\Models\Categoria;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    function index(): JsonResponse
    {
        $categorias = Categoria::all();

        return $this->successResponse(CategoriaResource::collection($categorias));
    }

    function getCategoria(string $id): JsonResponse
    {
        $categoria = Categoria::query()->find($id);

        if(is_null($categoria)) {
            return $this->errorResponse('La categoría no existe');
        }

        return $this->successResponse(new CategoriaResource($categoria));
    }

    function newCategoria(Request $request): JsonResponse
    {
        $data = $request->validate([
            'nombre' => 'required|string',
            'foto' => 'required|string' // handle logic to upload photo -> send url to db
        ]);

        $categoria = Categoria::query()->create([
            'nombre' => $data['nombre'],
            'foto' => $data['foto']
        ]);

        return $this->successResponse(new CategoriaResource($categoria));
    }

    function deleteCategoria(string $id): JsonResponse
    {
        $categoria = Categoria::query()->find($id);

        if (is_null($categoria)) {
            return $this->errorResponse('La categoría no existe.');
        }

        $deletion = $categoria->delete();
        $message = $deletion == 1 ? 'La categoría ha sido eliminada correctamente' : 'Error al eliminar la categoría';

        return $this->successResponse('', $message);
    }

    function updateCategoria(Request $request, string $id): JsonResponse
    {
        $data = $request->validate([
            'nombre' => 'required|string',
            'foto' => 'required|string'
        ]);

        $categoria = Categoria::query()->find($id);

        if (is_null($categoria)) {
            return $this->errorResponse('La categoría no existe.');
        }

        $update = $categoria->update([
            'nombre' => $data['nombre'],
            'foto' => $data['foto']
        ]);
        $message = $update == 1 ? 'La categoría ha sido modificada correctamente.' : 'Error al modificar la categoría.';

        return $this->successResponse(new CategoriaResource($categoria), $message);
    }
}
