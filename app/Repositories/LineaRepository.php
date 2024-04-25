<?php

namespace App\Repositories;

use App\Models\Linea;
use Illuminate\Database\Eloquent\Collection;

class LineaRepository extends GeneralRepository
{
    private const ENTITY_NAME = 'Línea';
    public function __construct()
    {
        $this->setBuilderFromModel(Linea::query()->getModel());
        $this->setEntityName(self::ENTITY_NAME);
    }

    public function all(): Collection
    {
        return $this->getBuilder()->with(['producto'])->get();
    }

    public function findAllByIdPedido($id): Collection
    {
        return $this->getBuilder()->where('id_pedido', $id)->get();
    }
}
