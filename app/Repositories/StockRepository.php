<?php

namespace App\Repositories;

use App\Models\Stock;

class StockRepository extends GeneralRepository
{
    private const ENTITY_NAME = 'Stock';
    public function __construct()
    {
        $this->setBuilderFromModel(Stock::query()->getModel());
        $this->setEntityName(self::ENTITY_NAME);
    }
}
