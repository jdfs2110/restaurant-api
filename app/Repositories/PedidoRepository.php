<?php

namespace App\Repositories;

use App\Models\Pedido;
use Illuminate\Database\Eloquent\Collection;

class PedidoRepository extends GeneralRepository
{
    private const ENTITY_NAME = 'Pedido';
    public function __construct()
    {
        $this->setBuilderFromModel(Pedido::query()->getModel());
        $this->setEntityName(self::ENTITY_NAME);
    }

    public function findPedidosByIdUsuario($id): Collection
    {
        return Pedido::query()->where('id_usuario', $id)->get();
    }

    public function findPedidosByIdMesa($id): Collection
    {
        return Pedido::query()->where('id_mesa', $id)->get();
    }

    public function findLastPedidoByIdMesa($id): Pedido
    {
        return Pedido::query()->where('id_mesa', $id)->get()->last();
    }
}
