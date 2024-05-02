<?php

namespace App\Repositories;

use App\Exceptions\LineaDuplicadaException;
use App\Models\Linea;
use Illuminate\Database\Eloquent\Collection;

class LineaRepository extends GeneralRepository
{
    private const ENTITY_NAME = 'Línea';
    public function __construct()
    {
        $this->setBuilderFromModel(Linea::query()->getModel());
        $this->setNotFoundMessage(self::ENTITY_NAME . ' no encontrada.');
    }

    public function all(): Collection
    {
        return $this->getBuilder()->with(['producto'])->get();
    }

    public function findAllByIdPedido(int $id): Collection
    {
        return $this->getBuilder()->where('id_pedido', $id)->get();
    }

    /**
     * @param int $idPedido ID del pedido
     * @param int $idProducto ID del producto
     * @throws LineaDuplicadaException cuando la línea ya existe
     */
    public function checkDuplicate(int $idPedido, int $idProducto): void
    {
        $count = $this->getBuilder()->getModel()->where('id_pedido', $idPedido)->where('id_producto', $idProducto)->count();

        if ($count >= 1) {
            throw new LineaDuplicadaException('La línea ya existe');
        }
    }
}
