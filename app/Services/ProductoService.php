<?php

namespace App\Services;

use App\Exceptions\NoContentException;
use App\Models\Producto;
use App\Repositories\ProductoRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use function Aws\map;

class ProductoService
{
    public function __construct(
        public readonly ProductoRepository $repository
    )
    {
    }

    private const PAGINATION_LIMIT = 10;
    /**
     * @param int $pagina Número de página que se desea obtener
     * @throws NoContentException cuuando la página está vacía
     * @return Collection Los productos de la página deseada
     */
    public function paginated(int $pagina): \Illuminate\Support\Collection
    {
        $productos = collect(DB::query()
            ->select([
                'productos.id',
                'productos.nombre',
                'productos.precio',
                'productos.activo',
                'productos.foto',
                'productos.id_categoria',
                'categorias.nombre as categoria',
                'stock.cantidad',
            ])->from('productos')
            ->join('categorias', 'categorias.id', '=', 'productos.id_categoria')
            ->join('stock', 'productos.id', '=', 'stock.id_producto')
            ->forPage($pagina, self::PAGINATION_LIMIT)
            ->get());

        $productos = $productos->map(function ($producto) {
            $producto->foto = env('CLOUDFLARE_R2_URL'). '/' . $producto->foto;
            return $producto;
        });

        if ($productos->isEmpty()) {
            throw new NoContentException('No hay productos.');
        }

        return $productos;
    }

    public function findModelOrFail($id): \Illuminate\Database\Eloquent\Builder|array|Collection|\Illuminate\Database\Eloquent\Model
    {
        return Producto::query()->findOrFail($id);
    }

    /**
     * @return int La cantidad de productos existentes en la Base de Datos
     */
    public function getAmountOfProducts(): int
    {
        return $this->repository->all()->count();
    }

    /**
     * @return int El límite de productos por cada petición
     */
    public function getPaginationLimit(): int
    {
        return self::PAGINATION_LIMIT;
    }

    /**
     * @param int $id ID de la categoría
     * @throws NoContentException cuando la categoría no tiene productos
     * @return Collection Los productos de la categoría seleccionada
     */
    public function findAllByIdCategoria(int $id): \Illuminate\Support\Collection
    {
        $productos = collect(DB::query()
            ->select([
                'productos.id',
                'productos.nombre',
                'productos.precio',
                'productos.activo',
                'productos.foto',
                'productos.id_categoria',
                'categorias.nombre as categoria',
                'stock.cantidad',
            ])->from('productos')
            ->join('categorias', 'categorias.id', '=', 'productos.id_categoria')
            ->join('stock', 'productos.id', '=', 'stock.id_producto')
            ->where('productos.id_categoria', '=', $id)
            ->get());

        $productos = $productos->map(function ($producto) {
            $producto->foto = env('CLOUDFLARE_R2_URL'). '/' . $producto->foto;
            return $producto;
        });

        if ($productos->isEmpty()) {
            throw new NoContentException('No hay productos.');
        }

        return $productos;
    }
}
