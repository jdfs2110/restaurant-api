<?php

namespace App\Http\Controllers\api;

use App\Events\LineaBarraCreatedEvent;
use App\Events\LineaBarraDeletedEvent;
use App\Events\LineaBarraEditedEvent;
use App\Events\LineaCocinaCreatedEvent;
use App\Events\LineaCocinaDeletedEvent;
use App\Events\LineaCocinaEditedEvent;
use App\Events\LineaCompletedEvent;
use App\Exceptions\LineaAlreadyCompletedException;
use App\Exceptions\ModelNotFoundException;
use App\Exceptions\NegativeQuantityException;
use App\Exceptions\NoContentException;
use App\Exceptions\PedidoAlreadyServedException;
use App\Http\Controllers\Controller;
use App\Repositories\LineaRepository;
use App\Repositories\MesaRepository;
use App\Repositories\PedidoRepository;
use App\Repositories\ProductoRepository;
use App\Resources\LineaResource;
use App\Services\LineaService;
use App\Services\PedidoService;
use App\Services\ProductoService;
use App\Services\StockService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use TypeError;

class LineaController extends Controller
{
    public function __construct(
        public readonly LineaRepository    $repository,
        public readonly LineaService       $service,
        public readonly ProductoRepository $productoRepository,
        public readonly PedidoRepository   $pedidoRepository,
        public readonly StockService       $stockService,
        public readonly PedidoService      $pedidoService,
        public readonly MesaRepository     $mesaRepository
    )
    {
    }

    function index(Request $request): JsonResponse
    {
        try {
            $pagina = $request->query('page', 1);

            $lineas = $this->service->paginated($pagina);

            return $this->successResponse(LineaResource::collection($lineas), "Lineas de la página $pagina");

        } catch (NoContentException $e) {
            return $this->errorResponse($e->getMessage(), 204);

        } catch (Exception) {
            return $this->unhandledErrorResponse();
        }
    }

    function getAmountOfPages(): JsonResponse
    {
        try {
            $lineas = $this->service->getAmountOfLineas();

            $limit = $this->service->getPaginationLimit();

            return $this->successResponse($lineas, $limit);

        } catch (Exception) {
            return $this->unhandledErrorResponse();
        }
    }

    function getLinea($id): JsonResponse
    {
        try {
            $linea = $this->repository->findOrFail($id);

            return $this->successResponse(new LineaResource($linea));

        } catch (TypeError) {
            return $this->errorResponse("Debes de introducir un número. (Valor introducido: $id)", 400);

        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage());

        } catch (Exception) {
            return $this->unhandledErrorResponse();
        }
    }

    function newLinea(Request $request): JsonResponse
    {
        try {
            $data = $request->validate([
                'precio' => 'required|numeric',
                'cantidad' => 'required|int|min:1',
                'id_producto' => 'required|int',
                'id_pedido' => 'required|int',
                'tipo' => 'required|cocinaobarra'
            ]);

            $tipo = strtolower($data['tipo']);

            $this->productoRepository->findOrFail($data['id_producto']);
            $this->pedidoRepository->findOrFail($data['id_pedido']);

            $this->stockService->reduceStock($data['id_producto'], $data['cantidad']);

            $linea = $this->repository->create([
                'precio' => $data['precio'],
                'cantidad' => $data['cantidad'],
                'id_producto' => $data['id_producto'],
                'id_pedido' => $data['id_pedido'],
                'tipo' => $tipo,
                'estado' => 0
            ]);

            if ($tipo == 'cocina') {
                event(new LineaCocinaCreatedEvent($linea, now()));
            } else if ($tipo == 'barra') {
                event(new LineaBarraCreatedEvent($linea, now()));
            }

            $this->pedidoService->recalculatePrice($data['id_pedido']);

            return $this->successResponse(new LineaResource($linea), 'Línea creada correctamente.', 201);

        } catch (ValidationException $e) {
            return $this->errorResponse($e->errors(), 400);

        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage());

        } catch (NegativeQuantityException|PedidoAlreadyServedException $e) {
            return $this->errorResponse($e->getMessage(), 400);

        } catch (Exception) {
            return $this->unhandledErrorResponse();
        }
    }

    function updateLinea(Request $request, $id): JsonResponse
    {
        try {
            $data = $request->validate([
                'precio' => 'required|numeric',
                'cantidad' => 'required|int|min:1',
                'id_producto' => 'required|int',
                'id_pedido' => 'required|int',
                'tipo' => 'required|cocinaobarra',
                'estado' => 'required|int|min:0,max:1'
            ]);

            $linea = $this->repository->findOrFail($id);
            $this->productoRepository->findOrFail($data['id_producto']);
            $this->pedidoRepository->findOrFail($data['id_pedido']);

            $this->stockService->updateStock($data['id_producto'], $data['cantidad'], $linea->getCantidad());

            $tipo = strtolower($data['tipo']);

            $update = $linea->update([
                'precio' => $data['precio'],
                'cantidad' => $data['cantidad'],
                'id_producto' => $data['id_producto'],
                'id_pedido' => $data['id_pedido'],
                'tipo' => $tipo,
                'estado' => $data['estado']
            ]);
            $message = $update == 1 ? 'La línea ha sido modificada correctamente.' : 'Error al modificar la línea';

            $this->pedidoService->recalculatePrice($data['id_pedido']);

            if ($tipo == 'cocina') {
                event(new LineaCocinaEditedEvent($linea));
            } else if ($tipo == 'barra') {
                event(new LineaBarraEditedEvent($linea));
            }

            return $this->successResponse(new LineaResource($linea), $message);

        } catch (TypeError) {
            return $this->errorResponse("Debes de introducir un número. (Valor introducido: $id)", 400);

        } catch (ValidationException $e) {
            return $this->errorResponse($e->errors(), 400);

        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage());

        } catch (PedidoAlreadyServedException|NegativeQuantityException $e) {
            return $this->errorResponse($e->getMessage(), 400);

        } catch (Exception) {
            return $this->unhandledErrorResponse();
        }
    }

    function deleteLinea($id): JsonResponse
    {
        try {
            $linea = $this->repository->findOrFail($id);
            $idPedido = $linea->id_pedido;
            $cantidad = $linea->cantidad;

            $tipo = $linea->getTipo();

            $deletion = $this->repository->delete($linea);
            $message = $deletion == 1 ? 'La línea ha sido eliminada correctamente' : 'Error al eliminar la línea';

            $this->pedidoService->recalculatePrice($idPedido, false);
            $this->stockService->addStock($cantidad);

            if ($tipo === 'cocina') {
                event(new LineaCocinaDeletedEvent($id));
            } else if ($tipo === 'barra') {
                event(new LineaBarraDeletedEvent($id));
            }

            return $this->successResponse('', $message);

        } catch (TypeError) {
            return $this->errorResponse("Debes de introducir un número. (Valor introducido: $id)", 400);

        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage());

        } catch (Exception $e) {
            return $this->unhandledErrorResponse($e->getMessage());
        }
    }

    function getLineasByPedido($id): JsonResponse
    {
        try {
            $this->pedidoRepository->findOrFail($id);
            $lineas = $this->service->findAllByIdPedido($id);

            return $this->successResponse(LineaResource::collection($lineas), "Lineas del pedido $id");

        } catch (TypeError) {
            return $this->errorResponse("Debes de introducir un número. (Valor introducido: $id)", 400);

        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage());

        } catch (NoContentException $e) {
            return $this->errorResponse($e->getMessage(), 204);

        } catch (Exception) {
            return $this->unhandledErrorResponse();
        }
    }

    function getLineasOfCocina(): JsonResponse
    {
        try {
            $lineas = $this->repository->getLineasOfCocina();

            return $this->successResponse(LineaResource::collection($lineas), 'Lineas de cocina');

        } catch (NoContentException) {
            return $this->errorResponse('', 204);

        } catch (Exception) {
            return $this->unhandledErrorResponse();
        }
    }

    function getLineasOfBarra(): JsonResponse
    {
        try {
            $lineas = $this->repository->getLineasOfBarra();

            return $this->successResponse(LineaResource::collection($lineas), 'Lineas de barra');

        } catch (NoContentException) {
            return $this->errorResponse('', 204);

        } catch (Exception) {
            return $this->unhandledErrorResponse();
        }
    }

    function completarLinea(int $id): JsonResponse
    {
        try {
            $linea = $this->service->completarLinea($id);
            $producto = $this->productoRepository->findOrFail($linea->getIdProducto());
            $pedido = $this->pedidoRepository->findOrFail($linea->getIdPedido());
            $mesa = $this->mesaRepository->findOrFail($pedido->id_mesa);

            if ($linea->getTipo() === 'cocina') {
                event(new LineaCocinaDeletedEvent($id));
            } else if ($linea->getTipo() === 'barra') {
                event(new LineaBarraDeletedEvent($id));
            }

            $message = 'Hay para recoger ' . $linea->getCantidad() . 'x ' . $producto->nombre . ' en ' . $linea->getTipo() . ' para la mesa ' . $mesa->id;

            event(new LineaCompletedEvent($id, $message, now()));

            return $this->successResponse('', "Línea $id completada correctamente.");

        } catch (TypeError) {
            return $this->errorResponse("Debes de introducir un número. (Valor introducido: $id)", 400);

        } catch (LineaAlreadyCompletedException $e) {
            return $this->errorResponse($e->getMessage(), 400);

        } catch (Exception) {
            return $this->unhandledErrorResponse();
        }
    }
}
