<?php

namespace App\Http\Controllers\api;

use App\Exceptions\ModelNotFoundException;
use App\Exceptions\NoContentException;
use App\Http\Controllers\Controller;
use App\Http\Resources\FacturaResource;
use App\Models\Pedido;
use App\Repositories\FacturaRepository;
use App\Repositories\PedidoRepository;
use App\Services\FacturaService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Factura;
use Illuminate\Validation\ValidationException;

class FacturaController extends Controller
{
    public function __construct(
        public readonly FacturaRepository $repository,
        public readonly PedidoRepository  $pedidoRepository,
        public readonly FacturaService    $service
    )
    {
    }

    function index(Request $request): JsonResponse
    {
        try {
            $pagina = $request->query('page', 1);

            $facturas = $this->service->paginated($pagina);

            return $this->successResponse(FacturaResource::collection($facturas), "Facturas de la página $pagina");

        } catch (NoContentException $e) {
            return $this->errorResponse($e->getMessage(), 204);
        }
    }

    function getAmountOfPages(): JsonResponse
    {
        $paginas = $this->service->getAmountOfPages();

        return $this->successResponse($paginas);
    }

    function getFactura($id): JsonResponse
    {
        try {
            $factura = $this->repository->findOrFail($id);

            return $this->successResponse(new FacturaResource($factura));
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage());
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
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
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage());
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
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
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage());
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    function deleteFactura($id): JsonResponse
    {
        try {
            $factura = $this->repository->findOrFail($id);

            $deletion = $this->repository->delete($factura);
            $message = $deletion == 1 ? 'La factura ha sido eliminada correctamente' : 'Error al eliminar la factura';

            return $this->successResponse('', $message);
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage());
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }

    }

    function getFacturaByPedido($id): JsonResponse
    {
        try {
            $this->pedidoRepository->findOrFail($id);
            $factura = $this->repository->findByIdPedido($id);

            return $this->successResponse(new FacturaResource($factura));
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage());
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }
}
