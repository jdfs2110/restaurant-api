<?php

namespace App\Http\Controllers\api;

use App\Exceptions\ModelNotFoundException;
use App\Exceptions\NegativeQuantityException;
use App\Http\Controllers\Controller;
use App\Http\Resources\LineaResource;
use App\Models\Linea;
use App\Models\Pedido;
use App\Repositories\LineaRepository;
use App\Repositories\PedidoRepository;
use App\Repositories\ProductoRepository;
use App\Services\PedidoService;
use App\Services\StockService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LineaController extends Controller
{
    public function __construct(
        public readonly LineaRepository    $repository,
        public readonly ProductoRepository $productoRepository,
        public readonly PedidoRepository   $pedidoRepository,
        public readonly StockService       $stockService,
        public readonly PedidoService      $pedidoService // <- si
    )
    {
    }

    function index(): JsonResponse
    {
        $lineas = $this->repository->all();

        return $this->successResponse(LineaResource::collection($lineas));
    }

    function getLinea($id): JsonResponse
    {
        try {
            $linea = $this->repository->findOrFail($id);

            return $this->successResponse(new LineaResource($linea));
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    function newLinea(Request $request): JsonResponse
    {
        try {
            $data = $request->validate([
                'precio' => 'required|numeric',
                'cantidad' => 'required|int|min:1',
                'id_producto' => 'required|int',
                'id_pedido' => 'required|int'
            ]);

            $this->productoRepository->findOrFail($data['id_producto']);
            $this->pedidoRepository->findOrFail($data['id_pedido']);
            $this->stockService->reduceStock($data['id_producto'], $data['cantidad']);

            $linea = $this->repository->create([
                'precio' => $data['precio'],
                'cantidad' => $data['cantidad'],
                'id_producto' => $data['id_producto'],
                'id_pedido' => $data['id_pedido']
            ]);

            $this->pedidoService->recalculatePrice($data['id_pedido']);

            return $this->successResponse(new LineaResource($linea), 'Línea creada correctamente.', 201);
        } catch (ValidationException $e) {
            return $this->errorResponse($e->errors(), 400);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    function updateLinea(Request $request, $id): JsonResponse
    {
        try {
            $data = $request->validate([
                'precio' => 'required|numeric',
                'cantidad' => 'required|int|min:1',
                'id_producto' => 'required|int',
                'id_pedido' => 'required|int'
            ]);

            $linea = $this->repository->findOrFail($id);
            $this->productoRepository->findOrFail($data['id_producto']);
            $this->pedidoRepository->findOrFail($data['id_pedido']);

            $this->stockService->updateStock($data['id_producto'], $data['cantidad'], $linea->getCantidad());

            $update = $linea->update([
                'precio' => $data['precio'],
                'cantidad' => $data['cantidad'],
                'id_producto' => $data['id_producto'],
                'id_pedido' => $data['id_pedido']
            ]);
            $this->pedidoService->recalculatePrice($data['id_pedido']);
            $message = $update == 1 ? 'La línea ha sido modificada correctamente.' : 'Error al modificar la línea';

            return $this->successResponse(new LineaResource($linea), $message);
        } catch (ValidationException $e) {
            return $this->errorResponse($e->errors(), 400);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    function deleteLinea($id): JsonResponse
    {
        try {
            $linea = $this->repository->findOrFail($id);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }

        $deletion = $this->repository->delete($linea);
        $message = $deletion == 1 ? 'La línea ha sido eliminada correctamente' : 'Error al eliminar la línea';

        return $this->successResponse('', $message);
    }

    function getLineasByPedido($id): JsonResponse
    {
        try {
            $this->pedidoRepository->findOrFail($id);
            $lineas = $this->repository->findAllByIdPedido($id);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }

        return $this->successResponse(LineaResource::collection($lineas));
    }
}
