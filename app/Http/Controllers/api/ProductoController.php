<?php

namespace App\Http\Controllers\api;

use App\Exceptions\ModelNotFoundException;
use App\Exceptions\NegativeQuantityException;
use App\Exceptions\NoContentException;
use App\Http\Controllers\Controller;
use App\Repositories\CategoriaRepository;
use App\Repositories\ProductoRepository;
use App\Repositories\StockRepository;
use App\Resources\ProductoResource;
use App\Resources\StockResource;
use App\Services\ProductoService;
use App\Services\StockService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\ItemNotFoundException;
use Illuminate\Validation\ValidationException;
use TypeError;

class ProductoController extends Controller
{
    public function __construct(
        public readonly ProductoRepository  $repository,
        public readonly ProductoService     $service,
        public readonly CategoriaRepository $categoriaRepository,
        public readonly StockService        $stockService,
        public readonly StockRepository     $stockRepository
    )
    {
    }

    function index(Request $request): JsonResponse
    {
        try {
            $pagina = $request->query('page', 1);

            $productos = $this->service->paginated($pagina);

            return $this->successResponse($productos, "Productos de la página $pagina");

        } catch (NoContentException $e) {
            return $this->errorResponse($e->getMessage(), 204);

        } catch (Exception) {
            return $this->unhandledErrorResponse();
        }
    }

    function getAmountOfPages(): JsonResponse
    {
        try {
            $products = $this->service->getAmountOfProducts();

            $limit = $this->service->getPaginationLimit();

            return $this->successResponse($products, $limit);

        } catch (Exception) {
            return $this->unhandledErrorResponse();
        }
    }

    function getProducto($id): JsonResponse
    {
        try {
            $producto = $this->repository->findOrFail($id);

            return $this->successResponse($producto);

        } catch (TypeError $e) {
            return $this->errorResponse("Debes de introducir un número. (Valor introducido: $id)", 400);

        } catch (ItemNotFoundException $e) {
            return $this->errorResponse('Producto no encontrado.');

        } catch (Exception) {
            return $this->unhandledErrorResponse();
        }
    }

    function newProducto(Request $request): JsonResponse
    {
        try {
            $data = $request->validate([
                'nombre' => 'required|string',
                'precio' => 'required|numeric',
                'id_categoria' => 'required|int',
                'cantidad' => 'required|int|min:0',
                'foto' => 'required|mimes:jpg,png,webp|max:2048'
            ]);

            $this->categoriaRepository->findOrFail($data['id_categoria']);

            $path = $request->file('foto')->store('productos', 'r2');

            $producto = $this->repository->create([
                'nombre' => $data['nombre'],
                'precio' => $data['precio'],
                'activo' => true,
                'id_categoria' => $data['id_categoria'],
                'foto' => $path
            ]);

            $this->stockService->addStock($producto->getId(), $data['cantidad']);

            $returning = $this->repository->findOrFail($producto->getId());

            return $this->successResponse($returning, 'Producto creado correctamente.', 201);

        } catch (ValidationException $e) {
            return $this->errorResponse($e->errors(), 400);

        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage());

        } catch (Exception) {
            return $this->unhandledErrorResponse();
        }
    }

    function deleteProducto($id): JsonResponse
    {
        try {
            $producto = $this->repository->findOrFail($id);
            $stock = $this->stockRepository->findByIdProducto($id);

            if (!is_null($stock)) {
                $stock->delete();
            }

            $this->deletePhotoIfExists($producto->getFoto());

            $deletion = $this->repository->delete($producto);
            $message = $deletion == 1 ? 'El producto ha sido eliminado correctamente' : 'Error al eliminar el producto';

            return $this->successResponse('', $message);

        } catch (TypeError) {
            return $this->errorResponse("Debes de introducir un número. (Valor introducido: $id)", 400);

        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage());

        } catch (Exception $e) {
            return $this->unhandledErrorResponse($e->getMessage());
        }
    }

    function getProductosByCategoria($id): JsonResponse
    {
        try {
            $this->categoriaRepository->findOrFail($id);

            $productos = $this->service->findAllByIdCategoria($id);

            return $this->successResponse($productos);

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

    function getProductStock($id): JsonResponse
    {
        try {
            $this->repository->findOrFail($id);

            $stock = $this->stockRepository->findByIdProductoOrFail($id);

            return $this->successResponse(new StockResource($stock));

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
    function updateProducto(Request $request, $id): JsonResponse
    {
        try {
            $data = $request->validate([
                'nombre' => 'required|string',
                'precio' => 'required|numeric',
                'activo' => 'required|boolean',
                'id_categoria' => 'required|int',
                'cantidad' => 'required|int|min:0',
                'foto' => 'nullable|mimes:jpg,png,webp|max:2048'
            ]);

            $producto = $this->repository->findOrFail($id);

            $this->categoriaRepository->findOrFail($data['id_categoria']);

            $notNull = !is_null($data['foto']);

            if ($notNull) {
                $path = $request->file('foto')->store('productos', 'r2');

                $this->deletePhotoIfExists($producto->foto);

                $producto->foto = ($path);
            }

            $producto->setNombre($data['nombre']);
            $producto->setPrecio($data['precio']);
            $producto->setActivo($data['activo']);
            $producto->setIdCategoria($data['id_categoria']);

            $update = $producto->save();
            $message = $update == 1 ? 'El producto ha sido modificado correctamente.' : 'Error al modificar el producto';

            $this->stockService->setStock($id, $data['cantidad']);

            $returning = $this->repository->findOrFail($producto->id);

            return $this->successResponse($returning, $message);

        } catch (TypeError) {
            return $this->errorResponse("Debes de introducir un número. (Valor introducido: $id)", 400);

        } catch (ValidationException $e) {
            return $this->errorResponse($e->errors(), 400);

        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage());

        } catch (Exception) {
            return $this->unhandledErrorResponse();
        }
    }

    function addStock(Request $request, $id): JsonResponse
    {
        try {
            $data = $request->validate([
                'cantidad' => 'required|int|min:0',
            ]);

            $this->repository->findOrFail($id);

            $this->stockService->addStock($id, $data['cantidad']);

            $updatedStock = $this->stockRepository->findByIdProducto($id);

            return $this->successResponse(new StockResource($updatedStock), 'Cantidad actualizada correctamente.');

        } catch (TypeError) {
            return $this->errorResponse("Debes de introducir un número. (Valor introducido: $id)", 400);

        } catch (ValidationException $e) {
            return $this->errorResponse($e->errors(), 400);

        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage());

        } catch (Exception) {
            return $this->unhandledErrorResponse();
        }
    }

    function reduceStock(Request $request, $id): JsonResponse
    {
        try {
            $data = $request->validate([
                'cantidad' => 'required|int|min:0',
            ]);

            $this->repository->findOrFail($id);

            $this->stockService->reduceStock($id, $data['cantidad']);

            $updatedStock = $this->stockRepository->findByIdProducto($id);

            return $this->successResponse(new StockResource($updatedStock), 'Cantidad actualizada correctamente.');

        } catch (TypeError) {
            return $this->errorResponse("Debes de introducir un número. (Valor introducido: $id)", 400);

        } catch (ValidationException $e) {
            return $this->errorResponse($e->errors(), 400);

        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage());

        } catch (NegativeQuantityException $e) {
            return $this->errorResponse($e->getMessage(), 400);

        } catch (Exception) {
            return $this->unhandledErrorResponse();
        }
    }

    public function getSimilarProducts($name): JsonResponse
    {
        try {
            $products = $this->repository->findSimilarProductsByName($name);

            return $this->successResponse(ProductoResource::collection($products), "Productos similares");
        } catch (Exception) {
            return $this->unhandledErrorResponse();
        }
    }
}
