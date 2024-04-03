<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductoResource;
use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    function index(): JsonResponse
    {
        $productos = Producto::all();

        return $this->successResponse(ProductoResource::collection($productos));
    }

    function getProducto($id): JsonResponse
    {
        $producto = Producto::query()->find($id);

        if (is_null($producto)) {
            return $this->errorResponse('El producto no existe.');
        }

        return $this->successResponse(new ProductoResource($producto));
    }


    function newProducto(Request $request): JsonResponse
    {
        $data = $request->validate([
            'nombre' => 'required|string',
            'precio' => 'required|numeric',
            'id_categoria' => 'required|int'
        ]);

        $producto = Producto::query()->create([
            'nombre' => $data['nombre'],
            'precio' => $data['precio'],
            'activo' => true,
            'id_categoria' => $data['id_categoria']
        ]);

        return $this->successResponse(new ProductoResource($producto));
    }

    function deleteProducto($id): JsonResponse
    {
        $producto = Producto::query()->find($id);

        if (is_null($producto)) {
            return $this->errorResponse('El producto no existe.');
        }

        $deletion = $producto->delete();
        $message = $deletion == 1 ? 'El producto ha sido eliminado correctamente' : 'Error al eliminar el producto';

        return $this->successResponse('', $message);
    }

    function getProductosByCategoria($id): JsonResponse
    {
        $categoria = Categoria::query()->find($id);

        if (is_null($categoria)) {
            return $this->errorResponse('La categoría no existe.');
        }

        $productos = Producto::query()->where('id_categoria', $id)->get();

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

        $producto = Producto::query()->find($id);

        if (is_null($producto)) {
            return $this->errorResponse('El producto no existe.');
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
