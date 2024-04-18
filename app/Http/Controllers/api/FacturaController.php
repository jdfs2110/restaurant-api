<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FacturaResource;
use App\Models\Pedido;
use App\Repositories\FacturaRepository;
use App\Repositories\PedidoRepository;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Factura;
use Illuminate\Validation\ValidationException;

class FacturaController extends Controller
{
    public function __construct(
        public readonly FacturaRepository $repository,
        public readonly PedidoRepository  $pedidoRepository
    )
    {
    }

    function index(): JsonResponse
    {
        $facturas = $this->repository->all();

        return $this->successResponse(FacturaResource::collection($facturas));
    }

    function getFactura($id): JsonResponse
    {
        try {
            $factura = $this->repository->findOrFail($id);

            return $this->successResponse(new FacturaResource($factura));
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }

    }

    function newFactura(Request $request): JsonResponse
    {
        try {
            $data = $request->validate([
                'id_pedido' => 'required|int'
            ]);

            $this->pedidoRepository->findOrFail($data['id_pedido']);

            $factura = $this->repository->create([
                'fecha' => now(),
                'id_pedido' => $data['id_pedido']
            ]);

            return $this->successResponse(new FacturaResource($factura), 'Factura creada correctamente', 201);
        } catch (ValidationException $e) {
            return $this->errorResponse($e->errors(), 400);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    function updateFactura(Request $request, $id): JsonResponse
    {
        try {
            $data = $request->validate([
                'id_pedido' => 'required|int'
            ]);

            $factura = $this->repository->findOrFail($id);
            $this->pedidoRepository->findOrFail($data['id_pedido']);

            $update = $factura->update([
                'id_pedido' => $data['id_pedido']
            ]);
            $message = $update == 1 ? 'La factura ha sido modificada correctamente.' : 'Error al modificar la factura.';

            return $this->successResponse(new FacturaResource($factura), $message);
        } catch (ValidationException $e) {
            return $this->errorResponse($e->errors(), 400);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    function deleteFactura($id): JsonResponse
    {
        try {
            $factura = $this->repository->findOrFail($id);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }

        $deletion = $this->repository->delete($factura);
        $message = $deletion == 1 ? 'La factura ha sido eliminada correctamente' : 'Error al eliminar la factura';

        return $this->successResponse('', $message);
    }

    function getFacturaByPedido($id): JsonResponse
    {
        try {
            $this->pedidoRepository->findOrFail($id);
            $factura = $this->repository->findByIdPedido($id);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }

        return $this->successResponse(new FacturaResource($factura));
    }
}
