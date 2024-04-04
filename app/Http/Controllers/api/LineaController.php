<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\LineaResource;
use App\Models\Linea;
use App\Models\Pedido;
use App\Repositories\LineaRepository;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LineaController extends Controller
{
    public function __construct(
        public readonly LineaRepository $repository
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

        $linea = Linea::query()->create([
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

        $linea = Linea::query()->find($id);

        if (is_null($linea)) {
            return $this->errorResponse('La línea no existe.');
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
        $linea = Linea::query()->find($id);

        if (is_null($linea)) {
            return $this->errorResponse('La línea no existe.');
        }

        $deletion = $linea->delete();
        $message = $deletion == 1 ? 'La línea ha sido eliminada correctamente' : 'Error al eliminar la línea';

        return $this->successResponse('', $message);
    }

    function getLineasByPedido($id): JsonResponse
    {
        $pedido = Pedido::query()->find($id);

        if (is_null($pedido)) {
            return $this->errorResponse('El pedido no existe.');
        }

        $lineas = Linea::query()->where('id_pedido', $id)->get();

        return $this->successResponse(LineaResource::collection($lineas));
    }
}
