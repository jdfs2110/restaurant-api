<?php

namespace App\Repositories;

use App\Models\Pedido;

class PedidoRepository extends GeneralRepository
{
    private const ENTITY_NAME = 'Pedido';
    public function __construct()
    {
        $this->setBuilderFromModel(Pedido::query()->getModel());
        $this->setEntityName(self::ENTITY_NAME);
    }
}
