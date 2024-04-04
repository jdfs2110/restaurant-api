<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\LineaResource;
use App\Models\Linea;
use App\Models\Pedido;
use App\Repositories\LineaRepository;
use App\Repositories\PedidoRepository;
use App\Services\PedidoService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LineaController extends Controller
{
    public function __construct(
        public readonly LineaRepository  $repository,
        public readonly PedidoRepository $pedidoRepository,
        public readonly PedidoService    $pedidoService // <- si
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
        $data = $request->validate([
            'precio' => 'required|numeric',
            'cantidad' => 'required|int|min:1',
            'id_producto' => 'required|int',
            'id_pedido' => 'required|int'
        ]);

        $linea = $this->repository->create([
            'precio' => $data['precio'],
            'cantidad' => $data['cantidad'],
            'id_producto' => $data['id_producto'],
            'id_pedido' => $data['id_pedido']
        ]);

        return $this->successResponse(new LineaResource($linea));
    }

    function updateLinea(Request $request, $id): JsonResponse
    {
        $data = $request->validate([
            'precio' => 'required|numeric',
            'cantidad' => 'required|int|min:1',
            'id_producto' => 'required|int',
            'id_pedido' => 'required|int'
        ]);

        try {
            $linea = $this->repository->findOrFail($id);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }

        try {
            $update = $linea->update([
                'precio' => $data['precio'],
                'cantidad' => $data['cantidad'],
                'id_producto' => $data['id_producto'],
                'id_pedido' => $data['id_pedido']
            ]);
            $message = $update == 1 ? 'La línea ha sido modificada correctamente.' : 'Error al modificar la línea';

            return $this->successResponse(new LineaResource($linea), $message);
        } catch (Exception $e) {
            return $this->errorResponse('Ha ocurrido un error.', 400);
        }
    }

    function deleteLinea($id): JsonResponse
    {
        try {
            $linea = $this->repository->findOrFail($id);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }

        $deletion = $linea->delete();
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
