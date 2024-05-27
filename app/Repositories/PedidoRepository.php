<?php

namespace App\Repositories;

use App\Exceptions\ModelNotFoundException;
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

    public function findPedidosByIdMesa(int $id): Collection
    {
        return $this->getBuilder()->where('id_mesa', $id)->get();
    }

    /**
     * @throws ModelNotFoundException
     */
    public function findLastPedidoByIdMesa(int $id): Pedido
    {
        $pedido = $this->getBuilder()->where('id_mesa', $id)->get()->last();

        if (is_null($pedido)) {
            throw new ModelNotFoundException('Pedido no encontrado.');
        }

        return $pedido;
    }
}
