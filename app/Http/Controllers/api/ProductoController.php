<?php

namespace App\Http\Controllers\api;

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
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    function newProducto(Request $request): JsonResponse
    {
        $data = $request->validate([
            'nombre' => 'required|string',
            'precio' => 'required|numeric',
            'id_categoria' => 'required|int',
            'cantidad' => 'required|int|min:0'
        ]);

        try {
            $this->categoriaRepository->findOrFail($data['id_categoria']);

            $producto = $this->repository->create([
                'nombre' => $data['nombre'],
                'precio' => $data['precio'],
                'activo' => true,
                'id_categoria' => $data['id_categoria']
            ]);

            $this->stockService->addStock($producto->getId(), $data['cantidad']);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }

        return $this->successResponse(new ProductoResource($producto), 'Producto creado correctamente.');
    }

    function deleteProducto($id): JsonResponse
    {
        try {
            $producto = $this->repository->findOrFail($id);
            $stock = $this->stockRepository->findByIdProducto($id);
            if (!is_null($stock)) {
                $stock->delete();
            }
            $deletion = $this->repository->delete($producto);
            $message = $deletion == 1 ? 'El producto ha sido eliminado correctamente' : 'Error al eliminar el producto';

            return $this->successResponse('', $message);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }

    }

    function getProductosByCategoria($id): JsonResponse
    {
        try {
            $this->categoriaRepository->findOrFail($id);
            $productos = $this->repository->findAllByIdCategoria($id);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }

        return $this->successResponse(ProductoResource::collection($productos));
    }

    function updateProducto(Request $request, $id): JsonResponse
    {
        $data = $request->validate([
            'nombre' => 'required|string',
            'precio' => 'required|numeric',
            'activo' => 'required|boolean',
            'id_categoria' => 'required|int',
            'cantidad' => 'required|int|min:0'
        ]);

        try {
            $producto = $this->repository->findOrFail($id);

            $this->categoriaRepository->findOrFail($data['id_categoria']);

            $update = $producto->update([
                'nombre' => $data['nombre'],
                'precio' => $data['precio'],
                'activo' => $data['activo'],
                'id_categoria' => $data['id_categoria']
            ]);

            $this->stockService->setStock($id, $data['cantidad']);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }

        $message = $update == 1 ? 'El producto ha sido modificado correctamente.' : 'Error al modificar el producto';

        return $this->successResponse(new ProductoResource($producto), $message);
    }
}
