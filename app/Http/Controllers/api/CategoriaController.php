<?php

namespace App\Http\Controllers\api;

use App\Exceptions\ModelNotFoundException;
use App\Exceptions\NoContentException;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoriaResource;
use App\Repositories\CategoriaRepository;
use App\Repositories\ProductoRepository;
use App\Services\CategoriaService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CategoriaController extends Controller
{
    public function __construct(
        public readonly CategoriaRepository $repository,
        public readonly ProductoRepository  $productoRepository,
        public readonly CategoriaService    $service
    )
    {
    }

    function index(Request $request): JsonResponse
    {
        try {
            $pagina = $request->get('page', 1);

            $categorias = $this->service->paginated($pagina);

            return $this->successResponse(CategoriaResource::collection($categorias), "Categorias de la página $pagina");

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

    function getCategoria(string $id): JsonResponse
    {
        try {
            $categoria = $this->repository->findOrFail($id);

            return $this->successResponse(new CategoriaResource($categoria));

        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage());

        } catch (Exception $e) {
            return $this->unhandledErrorResponse($e->getMessage());
        }
    }

    function newCategoria(Request $request): JsonResponse
    {
        try {
            $data = $request->validate([
                'nombre' => 'required|string',
                'foto' => 'required|mimes:jpg,png,webp|max:2048'
            ]);

            $path = $request->file('foto')->store('categorias', 'r2');

            $categoria = $this->repository->create([
                'nombre' => $data['nombre'],
                'foto' => $path
            ]);

            return $this->successResponse(new CategoriaResource($categoria), 'Categoría creada correctamente.', 201);

        } catch (ValidationException $e) {
            return $this->errorResponse($e->errors(), 400);

        } catch (Exception $e) {
            return $this->unhandledErrorResponse($e->getMessage());
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

            $this->deletePhotoIfExists($categoria->getFoto());

            $deletion = $this->repository->delete($categoria);
            $message = $deletion == 1 ? 'La categoría ha sido eliminada correctamente' : 'Error al eliminar la categoría';

            return $this->successResponse('', $message);

        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage());

        } catch (Exception $e) {
            return $this->unhandledErrorResponse($e->getMessage());
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
                $path = $request->file('foto')->store('categorias', 'r2');

                $this->deletePhotoIfExists($categoria->getFoto());

                $categoria->setFoto($path);
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
            return $this->unhandledErrorResponse($e->getMessage());
        }
    }
}
