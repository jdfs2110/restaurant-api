<?php

namespace App\Http\Controllers\api;

use App\Exceptions\ModelNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductoResource;
use App\Models\Categoria;
use App\Models\Producto;
use App\Repositories\CategoriaRepository;
use App\Repositories\ProductoRepository;
use App\Repositories\StockRepository;
use App\Services\StockService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ProductoController extends Controller
{
    public function __construct(
        public readonly ProductoRepository  $repository,
        public readonly CategoriaRepository $categoriaRepository,
        public readonly StockService        $stockService,
        public readonly StockRepository     $stockRepository
    )
    {
    }

    function index(): JsonResponse
    {
        $productos = $this->repository->all();

        return $this->successResponse(ProductoResource::collection($productos));
    }

    function getProducto($id): JsonResponse
    {
        try {
            $producto = $this->repository->findOrFail($id);

            return $this->successResponse(new ProductoResource($producto));
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage());
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
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

            $file = $request->file('foto');
            $fileName = time() . '-' . $file->hashName();
            $path = $file->storePubliclyAs('public/productos', $fileName);

            $producto = $this->repository->create([
                'nombre' => $data['nombre'],
                'precio' => $data['precio'],
                'activo' => true,
                'id_categoria' => $data['id_categoria'],
                'foto' => $fileName
            ]);

            $this->stockService->addStock($producto->getId(), $data['cantidad']);

            return $this->successResponse(new ProductoResource($producto), 'Producto creado correctamente.', 201);
        } catch (ValidationException $e) {
            return $this->errorResponse($e->errors(), 400);
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage());
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
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

            $this->deletePhotoIfExists($producto->getFoto(), 'productos');
            $deletion = $this->repository->delete($producto);
            $message = $deletion == 1 ? 'El producto ha sido eliminado correctamente' : 'Error al eliminar el producto';

            return $this->successResponse('', $message);
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage());
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    function getProductosByCategoria($id): JsonResponse
    {
        try {
            $this->categoriaRepository->findOrFail($id);
            $productos = $this->repository->findAllByIdCategoria($id);

            return $this->successResponse(ProductoResource::collection($productos));
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

            $null = is_null($data['foto']);

            if (!$null) {
                $fileName = $this->updatePhoto($request->file('foto'), $producto->getFoto(), 'productos', 'public/productos');

                $producto->setFoto($fileName);
            }

            $producto->setNombre($data['nombre']);
            $producto->setPrecio($data['precio']);
            $producto->setActivo($data['activo']);
            $producto->setIdCategoria($data['id_categoria']);

            $update = $producto->save();
            $message = $update == 1 ? 'El producto ha sido modificado correctamente.' : 'Error al modificar el producto';

            $this->stockService->setStock($id, $data['cantidad']);

            return $this->successResponse(new ProductoResource($producto), $message);
        } catch (ValidationException $e) {
            return $this->errorResponse($e->errors(), 400);
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage());
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }
}
