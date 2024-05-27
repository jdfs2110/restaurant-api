<?php

namespace App\Repositories;

use App\Exceptions\NoContentException;
use App\Models\Pedido;
use Illuminate\Database\Eloquent\Collection;

class PedidoRepository extends GeneralRepository
{
    private const ENTITY_NAME = 'Pedido';
    public function __construct()
    {
        $this->setBuilderFromModel(Pedido::query()->getModel());
        $this->setNotFoundMessage(self::ENTITY_NAME . ' no encontrado.');
    }

    public function findPedidosByIdUsuario(int $id): Collection
    {
        return $this->getBuilder()->where('id_usuario', $id)->get();
    }

    /**
     * @throws NoContentException
     */
    public function findPedidosByIdMesa(int $id): Collection
    {
        $pedidos = $this->getBuilder()->where('id_mesa', $id)->get();

        if ($pedidos->isEmpty()) {
            throw new NoContentException();
        }

        return $pedidos;
    }

    public function findLastPedidoByIdMesa(int $id): Pedido
    {
        return $this->getBuilder()->where('id_mesa', $id)->get()->last();
    }
}
