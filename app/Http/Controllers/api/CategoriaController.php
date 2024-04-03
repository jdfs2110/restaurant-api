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

        $response = [
            'categorias' => CategoriaResource::collection($categorias)
        ];

        return response()->json($response);
    }

    function getCategoria($id): JsonResponse
    {
        $categoria = Categoria::query()->where('id', $id)->get()->first();

        if(is_null($categoria)) {
            $errorMessage = [
                'error' => 'La categoría no existe'
            ];

            return response()->json($errorMessage, 404);
        }

        $response = [
            'categoria' => new CategoriaResource($categoria)
        ];

        return response()->json($response);
    }

    function newCategoria(Request $request): JsonResponse
    {
        $categoriaData = $request->validate([
            'nombre' => 'required|string',
            'foto' => 'required|string' // no se si hay que hacer algo para subir la foto
        ]);

        $categoria = Categoria::query()->create([
            'nombre' => $categoriaData['nombre'],
            'foto' => $categoriaData['foto']
        ]);

        $response = [
            'categoria' => new CategoriaResource($categoria)
        ];

        return response()->json($response);
    }

    function deleteCategoria($id): JsonResponse
    {
        $categoria = Categoria::query()->where('id', $id)->get()->first();

        if (is_null($categoria)) {
            $errorMessage = [
                'error' => 'La categoría no existe.'
            ];

            return response()->json($errorMessage, 404);
        }

        $deletion = Categoria::query()->where('id', $id)->delete();
        $message = $deletion == 1 ? 'La categoría ha sido eliminada correctamente' : 'Error al eliminar la categoría';

        $response = [
            'message' => $message
        ];

        return response()->json($response);
    }

    function updateCategoria(Request $request, $id): JsonResponse
    {
        $categoriaData = $request->validate([
            'nombre' => 'required|string',
            'foto' => 'required|string'
        ]);

        $categoria = Categoria::query()->where('id', $id)->get()->first();

        if (is_null($categoria)) {
            $errorMessage = [
                'error' => 'La categoría no existe.'
            ];

            return response()->json($errorMessage, 404);
        }

        $update = Categoria::query()->where('id', $id)->update([
            'nombre' => $categoriaData['nombre'],
            'foto' => $categoriaData['foto']
        ]);
        $message = $update == 1 ? 'La categoría ha sido modificada correctamente.' : 'Error al modificar la categoría.';

        $updatedCategoria = Categoria::query()->where('id', $id)->get()->first();

        $response = [
            'categoria' => new CategoriaResource($updatedCategoria),
            'message' => $message
        ];

        return response()->json($response);
    }
}
