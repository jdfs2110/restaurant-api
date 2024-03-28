<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PedidoController extends Controller
{
    function index(): JsonResponse
    {
        $pedidos = Pedido::all();

        $response = [
            'pedidos' => $pedidos
        ];

        return response()->json($response);
    }

    function getPedido($id): JsonResponse
    {
        $pedido = Pedido::query()->where('id', $id)->get()->first();

        if (is_null($pedido)) {
            $errorMessage = [
                'error' => 'El pedido no existe.'
            ];

            return response()->json($errorMessage, 404);
        }

        $response = [
            'pedido' => $pedido
        ];

        return response()->json($response);
    }
}
