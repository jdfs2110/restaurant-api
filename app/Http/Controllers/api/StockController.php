<?php

namespace App\Http\Controllers\api;

use App\Exceptions\ModelNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Resources\StockResource;
use App\Repositories\ProductoRepository;
use App\Repositories\StockRepository;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

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

    function createStock(Request $request): JsonResponse
    {
        try {
            $data = $request->validate([
                'cantidad' => 'required|int',
                'id_producto' => 'required|int'
            ]);

            $this->productoRepository->findOrFail($data['id_producto']);

            $stock = $this->repository->create([
                'cantidad' => $data['cantidad'],
                'id_producto' => $data['id_producto']
            ]);

            return $this->successResponse(new StockResource($stock), 'Stock creado correctamente.', 201);
        } catch (ValidationException $e) {
            return $this->errorResponse($e->errors(), 400);
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage());
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    function updateStock(Request $request, $id): JsonResponse
    {
        try {
            $data = $request->validate([
                'cantidad' => 'required|int',
                'id_producto' => 'required|int',
            ]);

            $stock = $this->repository->findOrFail($id);
            $this->productoRepository->findOrFail($data['id_producto']);

            $update = $stock->update([
                'cantidad' => $data['cantidad'],
                'id_producto' => $data['id_producto']
            ]);
            $message = $update == 1 ? 'El stock ha sido modificado correctamente.' : 'Error al modificar el stock.';

            return $this->successResponse(new StockResource($stock), $message);
        } catch (ValidationException $e) {
            return $this->errorResponse($e->errors(), 400);
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage());
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }
}
