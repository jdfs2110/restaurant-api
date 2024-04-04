<?php

namespace App\Repositories;

use App\Models\Stock;
use Exception;
use Illuminate\Database\Eloquent\Model;

class StockRepository extends GeneralRepository
{
    private const ENTITY_NAME = 'Stock';
    public function __construct()
    {
        $this->setBuilderFromModel(Stock::query()->getModel());
        $this->setEntityName(self::ENTITY_NAME);
    }

    /**
     * @throws Exception when a product has no stock associated (really shouldn't happen)
     */
    public function findByIdProducto($id): Model
    {
        $stock = Stock::query()->where('id_producto', $id)->get()->first();

        if (is_null($stock)) {
            throw new Exception('El producto no tiene stock asociado.'); // this shouldn't happen
        }

        return $stock;
    }
}
