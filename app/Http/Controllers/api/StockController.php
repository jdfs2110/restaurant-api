<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\StockResource;
use App\Models\Producto;
use App\Models\Stock;
use App\Repositories\StockRepository;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function __construct(
        public readonly StockRepository $repository
    )
    {
    }

    function index(): JsonResponse
    {
        $stock = $this->repository->all();

        return $this->successResponse(StockResource::collection($stock));
    }

    // findStockById
    function getStock($id): JsonResponse
    {
        try {
        $stock = $this->repository->findOrFail($id);

        return $this->successResponse(new StockResource($stock));
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    // find stock of a product
    function getProductStock($id): JsonResponse
    {
        $producto = Producto::query()->find($id);

        if (is_null($producto)) {
            return $this->errorResponse('El producto no existe.');
        }

        $stock = Stock::query()->where('id_producto', $id)->get()->first();

        if (is_null($stock)) {
            return $this->errorResponse('El producto no tiene stock asociado.'); // this shouldn't happen
        }

        return $this->successResponse(new StockResource($stock));
    }

    function createStock(Request $request): JsonResponse
    {
        $data = $request->validate([
            'cantidad' => 'required|int',
            'id_producto' => 'required|int'
        ]);

        $stock = Stock::query()->create([
            'cantidad' => $data['cantidad'],
            'id_producto' => $data['id_producto']
        ]);

        return $this->successResponse(new StockResource($stock));
    }

    function updateStock(Request $request, $id): JsonResponse
    {
        $data = $request->validate([
            'cantidad' => 'required|int',
            'id_producto' => 'required|int',
        ]);

        $stock = Stock::query()->find($id);

        if (is_null($stock)) {
            return $this->errorResponse('Este stock no existe.');
        }

        $update = $stock->update([
            'cantidad' => $data['cantidad'],
            'id_producto' => $data['id_producto']
        ]);
        $message = $update == 1 ? 'El stock ha sido modificado correctamente.' : 'Error al modificar el stock.';

        return $this->successResponse(new StockResource($stock), $message);
    }

    function deleteStock($id): JsonResponse
    {
        $stock = Stock::query()->find($id);

        if (is_null($stock)) {
            return $this->errorResponse('Este stock no existe.');
        }

        $deletion = $stock->delete();
        $message = $deletion == 1 ? 'Este stock ha sido eliminado correctamente' : 'Error al eliminar este stock';

        return $this->successResponse('', $message);
    }
}
