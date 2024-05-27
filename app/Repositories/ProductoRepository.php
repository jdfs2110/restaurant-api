<?php

namespace App\Repositories;

use App\Models\Producto;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ProductoRepository extends GeneralRepository
{
    private const ENTITY_NAME = 'Producto';
    public function __construct()
    {
        $this->setBuilderFromModel(Producto::query()->getModel());
        $this->setNotFoundMessage(self::ENTITY_NAME . ' no encontrado.');
    }

    public function findOrFail(int $id): mixed
    {
        $producto =  DB::query()
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
            ->where('productos.id', $id)
            ->where('productos.deleted_at', '=', null)
            ->get()->firstOrFail();

        $producto->foto = env('CLOUDFLARE_R2_URL') . '/' . $producto->foto;

        return $producto;
    }

    public function findSimilarProductsByName(string $name): Collection
    {
        return $this->getBuilder()->where('nombre', $name)
            ->orWhere('nombre', 'like', "%$name%")->get();
    }
}
