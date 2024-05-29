<?php

namespace App\Http\Controllers\api;

use App\Exceptions\MesaDesocupadaException;
use App\Exceptions\ModelNotFoundException;
use App\Exceptions\NoContentException;
use App\Http\Controllers\Controller;
use App\Repositories\MesaRepository;
use App\Resources\MesaResource;
use App\Resources\PedidoResource;
use App\Services\MesaService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use TypeError;

class MesaController extends Controller
{
    public function __construct(
        public readonly MesaRepository $repository,
        public readonly MesaService    $service
    )
    {
    }

    function index(): JsonResponse
    {
        try {
            $mesas = $this->service->all();

            return $this->successResponse(MesaResource::collection($mesas));

        } catch (NoContentException $e) {
            return $this->errorResponse($e->getMessage(), 204);

        } catch (Exception $e) {
            return $this->unhandledErrorResponse($e->getMessage());
        }
    }

    function getMesa($id): JsonResponse
    {
        try {
            $mesa = $this->repository->findOrFail($id);

            return $this->successResponse(new MesaResource($mesa));

        } catch (TypeError) {
            return $this->errorResponse("Debes de introducir un número. (Valor introducido: $id)", 400);

        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage());

        } catch (Exception $e) {
            return $this->unhandledErrorResponse($e->getMessage());
        }
    }

    function newMesa(Request $request): JsonResponse
    {
        try {
            $data = $request->validate([
                'capacidad_maxima' => 'required|int|max:20',
                'estado' => 'required|int|max:2'
            ]);

            $mesa = $this->repository->create([
                'capacidad_maxima' => $data['capacidad_maxima'],
                'estado' => $data['estado']
            ]);

            return $this->successResponse(new MesaResource($mesa), 'Mesa creada correctamente.', 201);

        } catch (ValidationException $e) {
            return $this->errorResponse($e->errors(), 400);

        } catch (Exception $e) {
            return $this->unhandledErrorResponse($e->getMessage());
        }
    }

    function deleteMesa($id): JsonResponse
    {
        try {
            $mesa = $this->repository->findOrFail($id);

            $deletion = $this->repository->delete($mesa);
            $message = $deletion == 1 ? 'La mesa ha sido eliminada correctamente' : 'Error al eliminar la mesa';

            return $this->successResponse('', $message);

        } catch (TypeError) {
            return $this->errorResponse("Debes de introducir un número. (Valor introducido: $id)", 400);

        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage());

        } catch (Exception $e) {
            return $this->unhandledErrorResponse($e->getMessage());
        }
    }

    function updateMesa(Request $request, $id): JsonResponse
    {
        try {
            $data = $request->validate([
                'capacidad_maxima' => 'required|int|max:20',
                'estado' => 'required|int|max:2'
            ]);

            $mesa = $this->repository->findOrFail($id);

            $update = $mesa->update([
                'capacidad_maxima' => $data['capacidad_maxima'],
                'estado' => $data['estado']
            ]);
            $message = $update == 1 ? 'La mesa ha sido modificada correctamente.' : 'Error al modificar la mesa.';

            return $this->successResponse(new MesaResource($mesa), $message);

        } catch (TypeError) {
            return $this->errorResponse("Debes de introducir un número. (Valor introducido: $id)", 400);

        } catch (ValidationException $e) {
            return $this->errorResponse($e->errors(), 400);

        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage());

        } catch (Exception $e) {
            return $this->unhandledErrorResponse($e->getMessage());
        }
    }

    function getPedidosByMesa($id): JsonResponse
    {
        try {
            $pedidos = $this->service->getPedidosByMesa($id);

            return $this->successResponse(PedidoResource::collection($pedidos));

        } catch (TypeError) {
            return $this->errorResponse("Debes de introducir un número. (Valor introducido: $id)", 400);

        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage());

        } catch (NoContentException $e) {
            return $this->errorResponse($e->getMessage(), 204);

        } catch (Exception $e) {
            return $this->unhandledErrorResponse($e->getMessage());
        }
    }

    // Parece ser que está bien
    function getPedidoActual($id): JsonResponse
    {
        try {
            $pedido = $this->service->getPedidoActual($id);

            return $this->successResponse(new PedidoResource($pedido));

        } catch (TypeError) {
            return $this->errorResponse("Debes de introducir un número. (Valor introducido: $id)", 400);

        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage());

        } catch (MesaDesocupadaException $e) {
            return $this->errorResponse($e->getMessage(), 400);

        } catch (NoContentException) {
            return $this->errorResponse('', 204);

        } catch (Exception $e) {
            return $this->unhandledErrorResponse($e->getMessage());
        }
    }
}
