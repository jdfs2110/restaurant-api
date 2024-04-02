<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PedidoResource;
use App\Models\Pedido;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PedidoController extends Controller
{
    function index(): JsonResponse
    {
        $pedidos = Pedido::all();

        $response = [
            'pedidos' => PedidoResource::collection($pedidos)
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
            'pedido' => new PedidoResource($pedido)
        ];

        return response()->json($response);
    }

    function newPedido(Request $request): JsonResponse
    {
        $pedidoData = $request->validate([
            'precio' => 'numeric|required',
            'numero_comensales' => 'int|required|min:1',
            'id_mesa' => 'int|required',
            'id_usuario' => 'int|required'
        ]);

        $pedido = Pedido::query()->create([
            'fecha' => now(),
            'estado' => 0,
            'precio' => $pedidoData['precio'],
            'numero_comensales' => $pedidoData['numero_comensales'],
            'id_mesa' => $pedidoData['id_mesa'],
            'id_usuario' => $pedidoData['id_usuario']
        ]);

        $response = new PedidoResource($pedido);

        return response()->json($response);
    }
}
