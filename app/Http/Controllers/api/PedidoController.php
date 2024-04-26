<?php

namespace App\Http\Controllers\api;

use App\Events\PedidoCreatedEvent;
use App\Exceptions\ModelNotFoundException;
use App\Exceptions\NoContentException;
use App\Exceptions\UserIsNotWaiterException;
use App\Http\Controllers\Controller;
use App\Http\Resources\PedidoResource;
use App\Repositories\LineaRepository;
use App\Repositories\MesaRepository;
use App\Repositories\PedidoRepository;
use App\Repositories\UserRepository;
use App\Services\PedidoService;
use App\Services\UserService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PedidoController extends Controller
{
    public function __construct(
        public readonly PedidoRepository $repository,
        public readonly PedidoService    $service,
        public readonly MesaRepository   $mesaRepository,
        public readonly UserRepository   $userRepository,
        public readonly UserService      $userService,
        public readonly LineaRepository  $lineaRepository
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

    function getPedido(int $id): JsonResponse
    {
        try {
            $pedido = $this->repository->findOrFail($id);

            return $this->successResponse(new PedidoResource($pedido));

        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage());

        } catch (Exception $e) {
            return $this->unhandledErrorResponse($e->getMessage());
        }
    }

    function newPedido(Request $request): JsonResponse
    {
        try {
            $data = $request->validate([
                'precio' => 'required|numeric',
                'numero_comensales' => 'required|int|min:1',
                'id_mesa' => 'required|int',
                'id_usuario' => 'required|int'
            ]);

            $this->mesaRepository->findOrFail($data['id_mesa']);
            $this->userRepository->findOrFail($data['id_usuario']);

            $pedido = $this->repository->create([
                'fecha' => now(),
                'estado' => 0,
                'precio' => $data['precio'],
                'numero_comensales' => $data['numero_comensales'],
                'id_mesa' => $data['id_mesa'],
                'id_usuario' => $data['id_usuario']
            ]);

            event(new PedidoCreatedEvent($pedido));

            return $this->successResponse(new PedidoResource($pedido), 'Pedido creado correctamente.', 201);

        } catch (ValidationException $e) {
            return $this->errorResponse($e->errors(), 400);

        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage());

        } catch (Exception $e) {
            return $this->unhandledErrorResponse($e->getMessage());
        }
    }

    function updatePedido(Request $request, int $id): JsonResponse
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

            $this->userService->checkIfMesero($data['id_usuario']);

            $update = $pedido->update([
                'estado' => $data['estado'],
                'precio' => $data['precio'],
                'numero_comensales' => $data['numero_comensales'],
                'id_mesa' => $data['id_mesa'],
                'id_usuario' => $data['id_usuario']
            ]);
            $message = $update == 1 ? 'El pedido ha sido modificado correctamente.' : 'Error al modificar el pedido';

            return $this->successResponse(new PedidoResource($pedido), $message);

        } catch (ValidationException $e) {
            return $this->errorResponse($e->errors(), 400);

        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage());

        } catch (UserIsNotWaiterException $e) {
            return $this->errorResponse($e->getMessage(), 400);

        } catch (Exception $e) {
            return $this->unhandledErrorResponse($e->getMessage());
        }
    }

    function deletePedido(int $id): JsonResponse
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

        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage());

        } catch (Exception $e) {
            return $this->unhandledErrorResponse($e->getMessage());
        }
    }
}
