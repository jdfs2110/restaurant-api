<?php

namespace App\Http\Controllers\api;

use App\Exceptions\ModelNotFoundException;
use App\Exceptions\NoContentException;
use App\Http\Controllers\Controller;
use App\Repositories\ProductoRepository;
use App\Repositories\StockRepository;
use App\Resources\StockResource;
use App\Services\StockService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class StockController extends Controller
{
    public function __construct(
        public readonly StockRepository    $repository,
        public readonly StockService       $service,
        public readonly ProductoRepository $productoRepository
    )
    {
    }

    function index(Request $request): JsonResponse
    {
        try {
            $pagina = $request->query('page', 1);

            $stock = $this->service->paginated($pagina);

            return $this->successResponse(StockResource::collection($stock), "Stock de la página $pagina");

        } catch (NoContentException $e) {
            return $this->errorResponse($e->getMessage(), 204);

        } catch (Exception $e) {
            return $this->unhandledErrorResponse($e->getMessage());
        }
    }

    function getAmountOfPages(): JsonResponse
    {
        try {
            $paginas = $this->service->getAmountOfPages();

            return $this->successResponse($paginas);

        } catch (Exception $e) {
            return $this->unhandledErrorResponse($e->getMessage());
        }
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
            return $this->unhandledErrorResponse($e->getMessage());
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

        } catch (\TypeError) {
            return $this->errorResponse("Debes de introducir un número. (Valor introducido: $id)", 400);

        } catch (ValidationException $e) {
            return $this->errorResponse($e->errors(), 400);

        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage());

        } catch (Exception $e) {
            return $this->unhandledErrorResponse($e->getMessage());
        }
    }
}
