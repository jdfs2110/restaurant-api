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
            'precio' => 'required|numeric',
            'numero_comensales' => 'required|int|min:1',
            'id_mesa' => 'required|int',
            'id_usuario' => 'required|int'
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

    function updatePedido(Request $request, $id): JsonResponse
    {
        $pedidoData = $request->validate([
            'estado' => 'required|int|max:3',
            'precio' => 'required|numeric',
            'numero_comensales' => 'required|int|min:1',
            'id_mesa' => 'required|int',
            'id_usuario' => 'required|int'
        ]);

        $pedido = Pedido::query()->where('id', $id)->get()->first();

        if (is_null($pedido)) {
            $errorMessage = [
                'error' => 'El pedido no existe.'
            ];

            return response()->json($errorMessage, 404);
        }

        $update = Pedido::query()->where('id', $id)->update([
            'estado' => $pedidoData['estado'],
            'precio' => $pedidoData['precio'],
            'numero_comensales' => $pedidoData['numero_comensales'],
            'id_mesa' => $pedidoData['id_mesa'],
            'id_usuario' => $pedidoData['id_usuario']
        ]);
        $message = $update == 1 ? 'El pedido ha sido modificado correctamente.' : 'Error al modificar el pedido';

        $updatedPedido = Pedido::query()->where('id', $id)->get()->first();

        $response = [
            'pedido' => new PedidoResource($updatedPedido),
            'message' => $message
        ];

        return response()->json($response);
    }
}
