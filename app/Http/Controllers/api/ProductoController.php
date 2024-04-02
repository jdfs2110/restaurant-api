<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    function index(): JsonResponse
    {
        $productos = Producto::all();

        $response = [
            'productos' => $productos
        ];

        return response()->json($response);
    }

    function getProducto($id): JsonResponse
    {
        $producto = Producto::query()->where('id', $id)->get()->first();

        if (is_null($producto)) {
            $errorMessage = [
                'error' => 'El producto no existe.'
            ];

            return response()->json($errorMessage, 404);
        }

        $response = [
            'producto' => $producto
        ];

        return response()->json($response);
    }


    function newProducto(Request $request): JsonResponse
    {
        $productData = $request->validate([
            'nombre' => 'required|string',
            'precio' => 'required|numeric',
            'id_categoria' => 'required|int'
        ]);

        $producto = Producto::query()->create([
            'nombre' => $productData['nombre'],
            'precio' => $productData['precio'],
            'activo' => true,
            'id_categoria' => $productData['id_categoria']
        ]);

        $response = [
            'producto' => $producto
        ];

        return response()->json($response);
    }

    function deleteProducto($id): JsonResponse
    {
        $producto = Producto::query()->where('id', $id)->get()->first();

        if (is_null($producto)) {
            $errorMessage = [
                'error' => 'El producto no existe.'
            ];

            return response()->json($errorMessage, 404);
        }

        $deletion = Producto::query()->where('id', $id)->delete();
        $message = $deletion == 1 ? 'El producto ha sido eliminado correctamente' : 'Error al eliminar el producto';

        $response = [
            'message' => $message
        ];

        return response()->json($response);
    }

    function getProductosByCategoria($id): JsonResponse
    {
        $categoria = Categoria::query()->where('id', $id)->get()->first();

        if (is_null($categoria)) {
            $errorMessage = [
                'error' => 'La categoría no existe.'
            ];

            return response()->json($errorMessage, 404);
        }

        $productos = Producto::query()->where('id_categoria', $id)->get();

        if (is_null($productos)) {
            $errorMessage = [
                'error' => 'La categoría no tiene productos.'
            ];

            return response()->json($errorMessage, 404);
        }

        $response = [
            'productos' => $productos
        ];

        return response()->json($response);
    }
}
