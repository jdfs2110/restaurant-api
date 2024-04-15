<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoriaResource;
use App\Repositories\CategoriaRepository;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    public function __construct(
        public readonly CategoriaRepository $repository
    )
    {
    }

    function index(): JsonResponse
    {
        $categorias = $this->repository->all();

        return $this->successResponse(CategoriaResource::collection($categorias));
    }

    function getCategoria(string $id): JsonResponse
    {
        try {
            $categoria = $this->repository->findOrFail($id);

            return $this->successResponse(new CategoriaResource($categoria));
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    function newCategoria(Request $request): JsonResponse
    {
        $data = $request->validate([
            'nombre' => 'required|string',
            'foto' => 'required|mimes:jpg,png,webp|max:2048'
        ]);

        try {
            $file = $request->file('foto');
            $fileName = time() . '-' . $file->hashName();
            $path = $file->storePubliclyAs('public/categorias', $fileName);

            $categoria = $this->repository->create([
                'nombre' => $data['nombre'],
                'foto' => $fileName
            ]);

            return $this->successResponse(new CategoriaResource($categoria), 'Categoría creada correctamente.');
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    function deleteCategoria(string $id): JsonResponse
    {
        try {
            $categoria = $this->repository->findOrFail($id);

            $this->deletePhotoIfExists($categoria->getFoto(), 'categorias');

            $deletion = $this->repository->delete($categoria);
            $message = $deletion == 1 ? 'La categoría ha sido eliminada correctamente' : 'Error al eliminar la categoría';

            return $this->successResponse('', $message);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    function updateCategoria(Request $request, string $id): JsonResponse
    {
        $data = $request->validate([
            'nombre' => 'required|string',
            'foto' => 'required|string'
        ]);

        try {
            $categoria = $this->repository->findOrFail($id);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }

        $update = $categoria->update([
            'nombre' => $data['nombre'],
            'foto' => $data['foto']
        ]);
        $message = $update == 1 ? 'La categoría ha sido modificada correctamente.' : 'Error al modificar la categoría.';

        return $this->successResponse(new CategoriaResource($categoria), $message);
    }
}
