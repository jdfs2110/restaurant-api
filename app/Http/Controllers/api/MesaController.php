<?php

namespace App\Http\Controllers\api;

use App\Exceptions\ModelNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Resources\MesaResource;
use App\Models\Mesa;
use App\Repositories\MesaRepository;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class MesaController extends Controller
{
    public function __construct(
        public readonly MesaRepository $repository
    )
    {
    }

    function index(): JsonResponse
    {
        $mesas = $this->repository->all();

        return $this->successResponse(MesaResource::collection($mesas));
    }

    function getMesa($id): JsonResponse
    {
        try {
            $mesa = $this->repository->findOrFail($id);

            return $this->successResponse(new MesaResource($mesa));
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage());
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    function newMesa(Request $request): JsonResponse
    {
        try {
            $data = $request->validate([
                'capacidad_maxima' => 'required|int|max:15',
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
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    function deleteMesa($id): JsonResponse
    {
        try {
            $mesa = $this->repository->findOrFail($id);

            $deletion = $this->repository->delete($mesa);
            $message = $deletion == 1 ? 'La mesa ha sido eliminada correctamente' : 'Error al eliminar la mesa';

            return $this->successResponse('', $message);
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage());
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    function updateMesa(Request $request, $id): JsonResponse
    {
        try {
            $data = $request->validate([
                'capacidad_maxima' => 'required|int|max:15',
                'estado' => 'required|int|max:2'
            ]);

            $mesa = $this->repository->findOrFail($id);

            $update = $mesa->update([
                'capacidad_maxima' => $data['capacidad_maxima'],
                'estado' => $data['estado']
            ]);
            $message = $update == 1 ? 'La mesa ha sido modificada correctamente.' : 'Error al modificar la mesa.';

            return $this->successResponse(new MesaResource($mesa), $message);
        } catch (ValidationException $e) {
            return $this->errorResponse($e->errors(), 400);
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage());
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }
}
