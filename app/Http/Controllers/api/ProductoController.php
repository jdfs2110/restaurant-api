<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
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
}
