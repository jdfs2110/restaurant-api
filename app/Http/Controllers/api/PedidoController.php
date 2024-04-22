<?php

namespace App\Http\Controllers\api;

use App\Events\PedidoCreatedEvent;
use App\Exceptions\ModelNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Resources\PedidoResource;
use App\Repositories\LineaRepository;
use App\Repositories\MesaRepository;
use App\Repositories\PedidoRepository;
use App\Repositories\UserRepository;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PedidoController extends Controller
{
    public function __construct(
        public readonly PedidoRepository $repository,
        public readonly MesaRepository   $mesaRepository,
        public readonly UserRepository   $userRepository,
        public readonly LineaRepository  $lineaRepository
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
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage());
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    function newPedido(Request $request): JsonResponse
    {
        try {
            $data = $request->validate([
                'precio' => 'required|numeric',
                'numero_comensales' => 'required|int|min:1',
                'id_mesa' => 'required|int',
                'id_usuario' => 'required|int'
            ]);

            $this->mesaRepository->findOrFail($data['id_mesa']);
            $this->userRepository->findOrFail($data['id_usuario']);

            $pedido = $this->repository->create([
                'fecha' => now(),
                'estado' => 0,
                'precio' => $data['precio'],
                'numero_comensales' => $data['numero_comensales'],
                'id_mesa' => $data['id_mesa'],
                'id_usuario' => $data['id_usuario']
            ]);

            event(new PedidoCreatedEvent($pedido));
            return $this->successResponse(new PedidoResource($pedido), 'Pedido creado correctamente.', 201);

        } catch (ValidationException $e) {
            return $this->errorResponse($e->errors(), 400);
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage());
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    function updatePedido(Request $request, $id): JsonResponse
    {
        try {
            $data = $request->validate([
                'estado' => 'required|int|max:3',
                'precio' => 'required|numeric',
                'numero_comensales' => 'required|int|min:1',
                'id_mesa' => 'required|int',
                'id_usuario' => 'required|int'
            ]);

            $pedido = $this->repository->findOrFail($id);
            $this->mesaRepository->findOrFail($data['id_mesa']);
            $this->userRepository->findOrFail($data['id_usuario']);

            $update = $pedido->update([
                'estado' => $data['estado'],
                'precio' => $data['precio'],
                'numero_comensales' => $data['numero_comensales'],
                'id_mesa' => $data['id_mesa'],
                'id_usuario' => $data['id_usuario']
            ]);
            $message = $update == 1 ? 'El pedido ha sido modificado correctamente.' : 'Error al modificar el pedido';

            return $this->successResponse(new PedidoResource($pedido), $message);
        } catch (ValidationException $e) {
            return $this->errorResponse($e->errors(), 400);
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage());
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    function deletePedido($id): JsonResponse
    {
        try {
            $pedido = $this->repository->findOrFail($id);
            $lineas = $this->lineaRepository->findAllByIdPedido($id);

            if ($lineas->isNotEmpty()) {
                foreach ($lineas as $linea) {
                    $linea->delete();
                }
            }

            $deletion = $this->repository->delete($pedido);
            $message = $deletion == 1 ? 'El pedido ha sido eliminado correctamente' : 'Error al eliminar el pedido';

            return $this->successResponse('', $message);
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage());
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }
}
