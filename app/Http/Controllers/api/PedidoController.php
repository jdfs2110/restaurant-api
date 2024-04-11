<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PedidoResource;
use App\Repositories\MesaRepository;
use App\Repositories\PedidoRepository;
use App\Repositories\UserRepository;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PedidoController extends Controller
{
    public function __construct(
        public readonly PedidoRepository $repository,
        public readonly MesaRepository $mesaRepository,
        public readonly UserRepository $userRepository
    )
    {
    }

    function index(): JsonResponse
    {
        $pedidos = $this->repository->all();

        return $this->successResponse(PedidoResource::collection($pedidos));
    }

    function getPedido($id): JsonResponse
    {
        try {
            $pedido = $this->repository->findOrFail($id);

            return $this->successResponse(new PedidoResource($pedido));
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }

    }

    function newPedido(Request $request): JsonResponse
    {
        $data = $request->validate([
            'precio' => 'required|numeric',
            'numero_comensales' => 'required|int|min:1',
            'id_mesa' => 'required|int',
            'id_usuario' => 'required|int'
        ]);

        try {
            $this->mesaRepository->findOrFail($data['id_mesa']);
            $this->userRepository->findOrFail($data['id_usuario']);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }

        $pedido = $this->repository->create([
            'fecha' => now(),
            'estado' => 0,
            'precio' => $data['precio'],
            'numero_comensales' => $data['numero_comensales'],
            'id_mesa' => $data['id_mesa'],
            'id_usuario' => $data['id_usuario']
        ]);

        return $this->successResponse(new PedidoResource($pedido), 'Pedido creado correctamente.');
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

        try {
            $pedido = $this->repository->findOrFail($id);
            $this->mesaRepository->findOrFail($data['id_mesa']);
            $this->userRepository->findOrFail($data['id_usuario']);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
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

    function deletePedido($id): JsonResponse
    {
        try {
            $pedido = $this->repository->findOrFail($id);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }

        $deletion = $this->repository->delete($pedido);
        $message = $deletion == 1 ? 'El pedido ha sido eliminado correctamente' : 'Error al eliminar el pedido';

        return $this->successResponse('', $message);
    }
}
