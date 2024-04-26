<?php

namespace App\Repositories;

use App\Models\Stock;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class StockRepository extends GeneralRepository
{
    private const ENTITY_NAME = 'Stock';
    public function __construct()
    {
        $this->setBuilderFromModel(Stock::query()->getModel());
        $this->setEntityName(self::ENTITY_NAME);
    }

    public function all(): Collection
    {
        return $this->getBuilder()->with(['producto'])->get();
    }

    public function findByIdProducto($id): ?Model
    {
        return $this->getBuilder()->where('id_producto', $id)->get()->first();
    }

    /**
     * @throws Exception cuando no se encuentra Stock (no debería de suceder...)
     */
    public function findByIdProductoOrFail($id): Model
    {
        $stock = $this->findByIdProducto($id);

        if (is_null($stock)) {
            throw new Exception('El producto no tiene stock asociado.'); // this shouldn't happen
        }

        return $stock;
    }
}
