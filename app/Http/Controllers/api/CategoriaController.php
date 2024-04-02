<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    function index(): JsonResponse
    {
        $categorias = Categoria::all();

        $response = [
            'categorias' => $categorias
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
            'categoria' => $categoria
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
            'categoria' => $categoria
        ];

        return response()->json($response);
    }
}
