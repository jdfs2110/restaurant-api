<?php

namespace App\Http\Controllers\api;

use App\Exceptions\ModelNotFoundException;
use App\Exceptions\NoContentException;
use App\Http\Controllers\Controller;
use App\Repositories\CategoriaRepository;
use App\Repositories\ProductoRepository;
use App\Resources\CategoriaResource;
use App\Services\CategoriaService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use TypeError;

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

        } catch (Exception) {
            return $this->unhandledErrorResponse();
        }
    }

    function getAmountOfPages(): JsonResponse
    {
        try {
            $categories = $this->service->getAmountOfCategories();

            $limit = $this->service->getPaginationLimit();

            return $this->successResponse($categories, $limit);

        } catch (Exception) {
            return $this->unhandledErrorResponse();
        }
    }

    function getCategoria($id): JsonResponse
    {
        try {
            $categoria = $this->repository->findOrFail($id);

            return $this->successResponse(new CategoriaResource($categoria));

        } catch (TypeError) {
            return $this->errorResponse("Debes de introducir un número. (Valor introducido: $id)", 400);

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

        } catch (Exception) {
            return $this->unhandledErrorResponse();
        }
    }

    function deleteCategoria($id): JsonResponse
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

        } catch (TypeError) {
            return $this->errorResponse("Debes de introducir un número. (Valor introducido: $id)", 400);

        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage());

        } catch (Exception) {
            return $this->unhandledErrorResponse();
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

            $notNull = !is_null($data['foto']);

            if ($notNull) {
                $path = $request->file('foto')->store('categorias', 'r2');

                $this->deletePhotoIfExists($categoria->getFoto());

                $categoria->setFoto($path);
            }

            $categoria->setNombre($data['nombre']);

            $update = $categoria->save();
            $message = $update == 1 ? 'La categoría ha sido modificada correctamente.' : 'Error al modificar la categoría.';

            return $this->successResponse(new CategoriaResource($categoria), $message);

        } catch (TypeError) {
            return $this->errorResponse("Debes de introducir un número. (Valor introducido: $id)", 400);

        } catch (ValidationException $e) {
            return $this->errorResponse($e->errors(), 400);

        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage());

        } catch (Exception) {
            return $this->unhandledErrorResponse('¿Estás usando POST y _method=PUT?');
        }
    }

    public function getSimilarCategories($name): JsonResponse
    {
        try {
            $categories = $this->repository->findSimilarCategoriesByName($name);

            return $this->successResponse(CategoriaResource::collection($users), "Categorias similares");
        } catch (Exception) {
            return $this->unhandledErrorResponse();
        }
    }
}
