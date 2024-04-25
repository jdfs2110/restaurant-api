<?php

namespace App\Repositories;

use App\Models\Factura;
use Exception;
use Illuminate\Database\Eloquent\Model;

class FacturaRepository extends GeneralRepository
{
    private const ENTITY_NAME = 'Factura';
    public function __construct()
    {
        $this->setBuilderFromModel(Factura::query()->getModel());
        $this->setEntityName(self::ENTITY_NAME);
    }

    /**
     * @throws Exception when a factura of a pedido is not found
     */
    public function findByIdPedido(int $id): Model
    {
        $factura = $this->getBuilder()->where('id_pedido', $id)->get()->first();

        if (is_null($factura)) {
            throw new Exception('El pedido no tiene factura asociada.');
        }

        return $factura;
    }
}
