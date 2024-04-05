<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\StockResource;
use App\Models\Producto;
use App\Models\Stock;
use App\Repositories\ProductoRepository;
use App\Repositories\StockRepository;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function __construct(
        public readonly StockRepository    $repository,
        public readonly ProductoRepository $productoRepository
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
        try {
            $this->productoRepository->findOrFail($id);
            $stock = $this->repository->findByIdProductoOrFail($id);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }

        return $this->successResponse(new StockResource($stock));
    }

    function createStock(Request $request): JsonResponse
    {
        $data = $request->validate([
            'cantidad' => 'required|int',
            'id_producto' => 'required|int'
        ]);

        try {
            $this->productoRepository->findOrFail($data['id_producto']);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }

        $stock = $this->repository->create([
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

        try {
            $stock = $this->repository->findOrFail($id);
            $this->productoRepository->findOrFail($data['id_producto']);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
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
        try {
            $stock = $this->repository->findOrFail($id);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }

        $deletion = $this->repository->delete($stock);
        $message = $deletion == 1 ? 'El stock ha sido eliminado correctamente' : 'Error al eliminar el stock';

        return $this->successResponse('', $message);
    }
}
