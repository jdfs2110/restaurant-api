<?php

namespace App\Repositories;

use App\Models\Factura;

class FacturaRepository extends GeneralRepository
{
    private const ENTITY_NAME = 'Factura';
    public function __construct()
    {
        $this->setBuilderFromModel(Factura::query()->getModel());
        $this->setEntityName(self::ENTITY_NAME);
    }
}
