<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductoResource;
use App\Models\Categoria;
use App\Models\Producto;
use App\Repositories\CategoriaRepository;
use App\Repositories\ProductoRepository;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function __construct(
        public readonly ProductoRepository $repository,
        public readonly CategoriaRepository $categoriaRepository
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

    function newProducto(Request $request): JsonResponse
    {
        $data = $request->validate([
            'nombre' => 'required|string',
            'precio' => 'required|numeric',
            'id_categoria' => 'required|int'
        ]);

        try {
            $this->categoriaRepository->findOrFail($data['id_categoria']);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }

        $producto = $this->repository->create([
            'nombre' => $data['nombre'],
            'precio' => $data['precio'],
            'activo' => true,
            'id_categoria' => $data['id_categoria']
        ]);

        return $this->successResponse(new ProductoResource($producto));
    }

    function deleteProducto($id): JsonResponse
    {
        try {
            $producto = $this->repository->findOrFail($id);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }

        $deletion = $this->repository->delete($producto);
        $message = $deletion == 1 ? 'El producto ha sido eliminado correctamente' : 'Error al eliminar el producto';

        return $this->successResponse('', $message);
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
            'id_categoria' => 'required|int'
        ]);

        try {
            $producto = $this->repository->findOrFail($id);
            $this->categoriaRepository->findOrFail($data['id_categoria']);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }

        $update = $producto->update([
            'nombre' => $data['nombre'],
            'precio' => $data['precio'],
            'activo' => $data['activo'],
            'id_categoria' => $data['id_categoria']
        ]);
        $message = $update == 1 ? 'El producto ha sido modificado correctamente.' : 'Error al modificar el producto';

        return $this->successResponse(new ProductoResource($producto), $message);
    }
}
