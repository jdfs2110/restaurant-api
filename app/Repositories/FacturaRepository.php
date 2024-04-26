<?php

namespace App\Repositories;

use App\Exceptions\PedidoSinFacturaException;
use App\Models\Factura;
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
     * @throws PedidoSinFacturaException cuando el pedido no tiene una factura generada aún
     */
    public function findByIdPedido(int $id): Model
    {
        $factura = $this->getBuilder()->where('id_pedido', $id)->get()->first();

        if (is_null($factura)) {
            throw new PedidoSinFacturaException('El pedido no tiene factura asociada.');
        }

        return $factura;
    }
}
