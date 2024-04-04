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
        $data = $request->validate([
            'id_pedido' => 'required|int'
        ]);

        $factura = $this->repository->create([
            'fecha' => now(),
            'id_pedido' => $data['id_pedido']
        ]);

        return $this->successResponse(new FacturaResource($factura));
    }

    function updateFactura(Request $request, $id): JsonResponse
    {
        $data = $request->validate([
            'id_pedido' => 'required|int'
        ]);

        try {
            $factura = $this->repository->findOrFail($id);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }

        $update = $factura->update([
            'id_pedido' => $data['id_pedido']
        ]);
        $message = $update == 1 ? 'La factura ha sido modificada correctamente.' : 'Error al modificar la factura.';

        return $this->successResponse(new FacturaResource($factura), $message);
    }

    function deleteFactura($id): JsonResponse
    {
        try {
            $factura = $this->repository->findOrFail($id);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }

        $deletion = $factura->delete();
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
