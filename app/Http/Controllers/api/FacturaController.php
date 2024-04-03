<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FacturaResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Factura;

class FacturaController extends Controller
{
    function index(): JsonResponse
    {
        $facturas = Factura::all();

        return $this->successResponse(FacturaResource::collection($facturas));
    }

    function getFactura($id): JsonResponse
    {
        $factura = Factura::query()->find($id);

        if (is_null($factura)) {
            return $this->errorResponse('La factura no existe.');
        }

        return $this->successResponse(new FacturaResource($factura));
    }

    function newFactura(Request $request): JsonResponse
    {
        $data = $request->validate([
            'id_pedido' => 'required|int'
        ]);

        $factura = Factura::query()->create([
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

        $factura = Factura::query()->find($id);

        if (is_null($factura)) {
            return $this->errorResponse('La factura no existe.');
        }

        $update = $factura->update([
            'id_pedido' => $data['id_pedido']
        ]);
        $message = $update == 1 ? 'La factura ha sido modificada correctamente.' : 'Error al modificar la factura.';

        return $this->successResponse(new FacturaResource($factura), $message);
    }

    function deleteFactura($id): JsonResponse
    {
        $factura = Factura::query()->find($id);

        if (is_null($factura)) {
            return $this->errorResponse('La factura no existe.');
        }

        $deletion = $factura->delete();
        $message = $deletion == 1 ? 'La factura ha sido eliminada correctamente' : 'Error al eliminar la factura';

        return $this->successResponse('', $message);
    }
}
