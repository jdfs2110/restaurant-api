<?php

namespace App\Repositories;

use App\Models\Producto;

class ProductoRepository extends GeneralRepository
{
    private const ENTITY_NAME = 'Producto';
    public function __construct()
    {
        $this->setBuilderFromModel(Producto::query()->getModel());
        $this->setEntityName(self::ENTITY_NAME);
    }
}
