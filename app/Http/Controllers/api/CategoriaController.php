<?php

namespace App\Http\Controllers\api;

use App\Exceptions\ModelNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoriaResource;
use App\Repositories\CategoriaRepository;
use App\Repositories\ProductoRepository;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use function PHPUnit\Framework\isEmpty;

class CategoriaController extends Controller
{
    public function __construct(
        public readonly CategoriaRepository $repository,
        public readonly ProductoRepository  $productoRepository
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
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage());
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    function newCategoria(Request $request): JsonResponse
    {
        try {
            $data = $request->validate([
                'nombre' => 'required|string',
                'foto' => 'required|mimes:jpg,png,webp|max:2048'
            ]);

            $file = $request->file('foto');
            $fileName = time() . '-' . $file->hashName();
            $path = $file->storePubliclyAs('public/categorias', $fileName);

            $categoria = $this->repository->create([
                'nombre' => $data['nombre'],
                'foto' => $fileName
            ]);

            return $this->successResponse(new CategoriaResource($categoria), 'Categoría creada correctamente.', 201);

        } catch (ValidationException $e) {
            return $this->errorResponse($e->errors(), 400);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    function deleteCategoria(string $id): JsonResponse
    {
        try {
            $categoria = $this->repository->findOrFail($id);

            $productos = $this->productoRepository->findAllByIdCategoria($id);
            if ($productos->isNotEmpty()) {
                return $this->errorResponse('La categoría tiene productos.', 400);
            }

            $this->deletePhotoIfExists($categoria->getFoto(), 'categorias');

            $deletion = $this->repository->delete($categoria);
            $message = $deletion == 1 ? 'La categoría ha sido eliminada correctamente' : 'Error al eliminar la categoría';

            return $this->successResponse('', $message);
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage());
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     * @uses Este método requiere que sea hecho a través de POST, con '?_method=PUT' al final de la URL.
     */
    function updateCategoria(Request $request, $id): JsonResponse
    {

        try {
            $data = $request->validate([
                'nombre' => 'required|string',
                'foto' => 'nullable|mimes:jpg,png,webp|max:2048'
            ]);

            $categoria = $this->repository->findOrFail($id);

            $null = is_null($data['foto']);

            if (!$null) {
                $fileName = $this->updatePhoto($request->file('foto'), $categoria->getFoto(), 'categorias', 'public/categorias');

                $categoria->setFoto($fileName);
            }

            $categoria->setNombre($data['nombre']);

            $update = $categoria->save();
            $message = $update == 1 ? 'La categoría ha sido modificada correctamente.' : 'Error al modificar la categoría.';

            return $this->successResponse(new CategoriaResource($categoria), $message);
        } catch (ValidationException $e) {
            return $this->errorResponse($e->errors(), 400);
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage());
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }
}
