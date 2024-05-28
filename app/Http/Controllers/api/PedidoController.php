<?php

namespace App\Http\Controllers\api;

use App\Events\MesaEditedEvent;
use App\Exceptions\MesaOcupadaException;
use App\Exceptions\ModelNotFoundException;
use App\Exceptions\NoContentException;
use App\Exceptions\PedidoAlreadyServedException;
use App\Exceptions\PedidoEnCursoException;
use App\Exceptions\UserIsNotWaiterException;
use App\Http\Controllers\Controller;
use App\Models\Factura;
use App\Repositories\LineaRepository;
use App\Repositories\MesaRepository;
use App\Repositories\PedidoRepository;
use App\Repositories\UserRepository;
use App\Resources\PedidoResource;
use App\Services\MesaService;
use App\Services\PedidoService;
use App\Services\UserService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use TypeError;

class PedidoController extends Controller
{
    public function __construct(
        public readonly PedidoRepository $repository,
        public readonly PedidoService    $service,
        public readonly MesaRepository   $mesaRepository,
        public readonly MesaService      $mesaService,
        public readonly UserRepository   $userRepository,
        public readonly UserService      $userService,
        public readonly LineaRepository  $lineaRepository,
    )
    {
    }

    function index(Request $request): JsonResponse
    {
        try {
            $pagina = $request->query('page', 1);

            $pedidos = $this->service->paginated($pagina);

            return $this->successResponse(PedidoResource::collection($pedidos), "Pedidos de la página $pagina");

        } catch (NoContentException $e) {
            return $this->errorResponse($e->getMessage(), 204);

        } catch (Exception) {
            return $this->unhandledErrorResponse();
        }
    }

    function getAmountOfPages(): JsonResponse
    {
        try {
            $pedidos = $this->service->getAmountOfPedidos();

            $limit = $this->service->getPaginationLimit();

            return $this->successResponse($pedidos, $limit);

        } catch (Exception) {
            return $this->unhandledErrorResponse();
        }
    }

    function getPedido($id): JsonResponse
    {
        try {
            $pedido = $this->repository->findOrFail($id);

            return $this->successResponse(new PedidoResource($pedido));

        } catch (TypeError) {
            return $this->errorResponse("Debes de introducir un número. (Valor introducido: $id)", 400);

        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage());

        } catch (Exception) {
            return $this->unhandledErrorResponse();
        }
    }

    function newPedido(Request $request): JsonResponse
    {
        try {
            $data = $request->validate([
                'numero_comensales' => 'required|int|min:1',
                'id_mesa' => 'required|int',
                'id_usuario' => 'required|int'
            ]);

            $busy = $this->mesaService->checkIfBusy($data['id_mesa']);

//            $this->userService->checkIfMesero($data['id_usuario']);

            $pedido = $this->repository->create([
                'fecha' => now(),
                'estado' => 0,
                'precio' => 0,
                'numero_comensales' => $data['numero_comensales'],
                'id_mesa' => $data['id_mesa'],
                'id_usuario' => $data['id_usuario']
            ]);

            $mesa = $this->mesaService->setOcupada($busy);

            event(new MesaEditedEvent($mesa, "La mesa $mesa->id ha recibido un pedido"));

            return $this->successResponse(new PedidoResource($pedido), 'Pedido creado correctamente.', 201);

        } catch (ValidationException $e) {
            return $this->errorResponse($e->errors(), 400);

        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage());

        } catch (MesaOcupadaException $e) {
            return $this->errorResponse($e->getMessage(), 400);

        } catch (Exception) {
            return $this->unhandledErrorResponse();
        }
    }

    function updatePedido(Request $request, $id): JsonResponse
    {
        try {
            $data = $request->validate([
                'estado' => 'required|int|max:3',
                'precio' => 'required|numeric',
                'numero_comensales' => 'required|int|min:1',
                'id_mesa' => 'required|int',
                'id_usuario' => 'required|int'
            ]);

            $pedido = $this->repository->findOrFail($id);
            $this->mesaRepository->findOrFail($data['id_mesa']);

            $this->service->checkIfServido($pedido);
            $this->userService->checkIfMesero($data['id_usuario']);

            if ($data['estado'] === 2) {
                $this->service->servirPedido($id);
            }

            $update = $pedido->update([
                'estado' => $data['estado'],
                'precio' => $data['precio'],
                'numero_comensales' => $data['numero_comensales'],
                'id_mesa' => $data['id_mesa'],
                'id_usuario' => $data['id_usuario']
            ]);
            $message = $update == 1 ? 'El pedido ha sido modificado correctamente.' : 'Error al modificar el pedido';

            return $this->successResponse(new PedidoResource($pedido), $message);

        } catch (TypeError) {
            return $this->errorResponse("Debes de introducir un número. (Valor introducido: $id)", 400);

        } catch (ValidationException $e) {
            return $this->errorResponse($e->errors(), 400);

        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage());

        } catch (UserIsNotWaiterException|PedidoAlreadyServedException $e) {
            return $this->errorResponse($e->getMessage(), 400);

        } catch (Exception) {
            return $this->unhandledErrorResponse();
        }
    }

    function deletePedido($id): JsonResponse
    {
        try {
            $pedido = $this->repository->findOrFail($id);
            $lineas = $this->lineaRepository->findAllByIdPedido($id);

            if ($lineas->isNotEmpty()) {
                foreach ($lineas as $linea) {
                    $linea->delete();
                }
            }

            $deletion = $this->repository->delete($pedido);
            $message = $deletion == 1 ? 'El pedido ha sido eliminado correctamente' : 'Error al eliminar el pedido';

            return $this->successResponse('', $message);

        } catch (TypeError) {
            return $this->errorResponse("Debes de introducir un número. (Valor introducido: $id)", 400);

        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage());

        } catch (Exception) {
            return $this->unhandledErrorResponse();
        }
    }

    function servirPedido($id): JsonResponse
    {
        try {
            $mesa = $this->service->servirPedido($id);

            Factura::query()->create([
                'fecha' => now(),
                'id_pedido' => $id
            ]);

            event(new MesaEditedEvent($mesa, "El pedido $id ha sido servido"));
            return $this->successResponse('', 'Estado del pedido cambiado correctamente.');

        } catch (TypeError) {
            return $this->errorResponse("Debes de introducir un número. (Valor introducido: $id)", 400);

        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage());

        } catch (PedidoAlreadyServedException|PedidoEnCursoException $e) {
            return $this->errorResponse($e->getMessage(), 400);

        } catch (Exception) {
            return $this->unhandledErrorResponse();
        }
    }

    function cancelarPedido($id): JsonResponse
    {
        try {
            $this->service->cancelarPedido($id);

            return $this->successResponse('', 'Pedido cancelado correctamente.');
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage());

        } catch (TypeError) {
            return $this->errorResponse("Debes de introducir un número. (Valor introducido: $id)", 400);

        } catch (Exception $e) {
            dd($e);
            return $this->unhandledErrorResponse();
        }
    }
}
