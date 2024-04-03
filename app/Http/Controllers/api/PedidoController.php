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

        return $this->successResponse(PedidoResource::collection($pedidos));
    }

    function getPedido($id): JsonResponse
    {
        $pedido = Pedido::query()->find($id);

        if (is_null($pedido)) {
            return $this->errorResponse('El pedido no existe.');
        }

        return $this->successResponse(new PedidoResource($pedido));
    }

    function newPedido(Request $request): JsonResponse
    {
        $data = $request->validate([
            'precio' => 'required|numeric',
            'numero_comensales' => 'required|int|min:1',
            'id_mesa' => 'required|int',
            'id_usuario' => 'required|int'
        ]);

        $pedido = Pedido::query()->create([
            'fecha' => now(),
            'estado' => 0,
            'precio' => $data['precio'],
            'numero_comensales' => $data['numero_comensales'],
            'id_mesa' => $data['id_mesa'],
            'id_usuario' => $data['id_usuario']
        ]);

        return $this->successResponse(new PedidoResource($pedido));
    }

    function updatePedido(Request $request, $id): JsonResponse
    {
        $data = $request->validate([
            'estado' => 'required|int|max:3',
            'precio' => 'required|numeric',
            'numero_comensales' => 'required|int|min:1',
            'id_mesa' => 'required|int',
            'id_usuario' => 'required|int'
        ]);

        $pedido = Pedido::query()->find($id);

        if (is_null($pedido)) {
            return $this->errorResponse('El pedido no existe.');
        }

        $update = $pedido->update([
            'estado' => $data['estado'],
            'precio' => $data['precio'],
            'numero_comensales' => $data['numero_comensales'],
            'id_mesa' => $data['id_mesa'],
            'id_usuario' => $data['id_usuario']
        ]);
        $message = $update == 1 ? 'El pedido ha sido modificado correctamente.' : 'Error al modificar el pedido';

        return $this->successResponse(new PedidoResource($pedido), $message);
    }
}
