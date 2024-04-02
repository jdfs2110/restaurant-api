<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use App\Models\Stock;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StockController extends Controller
{
    function index(): JsonResponse
    {
        $stock = Stock::all();

        $response = [
            'stock' => $stock
        ];

        return response()->json($response);
    }

    // findStockById
    function getStock($id): JsonResponse
    {
        $stock = Stock::query()->where('id', $id)->get()->first();

        if (is_null($stock)) {
            $errorMessage = [
                'error' => 'Este stock no existe.'
            ];

            return response()->json($errorMessage, 404);
        }

        $response = [
            'stock' => $stock
        ];

        return response()->json($response);
    }

    // find stock of a product
    function getProductStock($id): JsonResponse
    {
        $producto = Producto::query()->where('id', $id)->get()->first();

        if (is_null($producto)) {
            $errorMessage = [
                'error' => 'El producto no existe.'
            ];

            return response()->json($errorMessage, 404);
        }

        $stock = Stock::query()->where('id_producto', $id)->get()->first();

        if (is_null($stock)) {
            $errorMessage = [
                'error' => 'El producto no tiene stock asociado.'
            ];

            return response()->json($errorMessage, 404);
        }

        $response = [
            'stock' => $stock
        ];

        return response()->json($response);
    }

    function createStock(Request $request): JsonResponse
    {
        $stockData = $request->validate([
            'cantidad' => 'required|int',
            'id_producto' => 'required|int'
        ]);

        $stock = Stock::query()->create([
            'cantidad' => $stockData['cantidad'],
            'id_producto' => $stockData['id_producto']
        ]);

        $response = [
            'stock' => $stock
        ];

        return response()->json($response);
    }

    function updateStock(Request $request, $id): JsonResponse
    {
        $stockData = $request->validate([
            'cantidad' => 'required|int',
            'id_producto' => 'required|int',
        ]);

        $stock = Stock::query()->where('id', $id)->get()->first();

        if (is_null($stock)) {
            $errorMessage = [
                'error' => 'Este stock no existe.'
            ];

            return response()->json($errorMessage, 404);
        }

        $update = Stock::query()->where('id', $id)->update([
            'cantidad' => $stockData['cantidad'],
            'id_producto' => $stockData['id_producto']
        ]);
        $message = $update == 1 ? 'El stock ha sido modificado correctamente.' : 'Error al modificar el stock.';

        $updatedStock = Stock::query()->where('id', $id)->get()->first();

        $response = [
            'stock' => $updatedStock,
            'message' => $message
        ];

        return response()->json($response);
    }
}
