<?php

namespace App\Repositories;

use App\Models\Producto;
use Illuminate\Database\Eloquent\Collection;

class ProductoRepository extends GeneralRepository
{
    private const ENTITY_NAME = 'Producto';
    public function __construct()
    {
        $this->setBuilderFromModel(Producto::query()->getModel());
        $this->setEntityName(self::ENTITY_NAME);
    }

    public function all(): Collection
    {
        return $this->getBuilder()->with(['categoria'])->get();
    }

    public function findAllByIdCategoria($id): Collection
    {
        return Producto::query()->where('id_categoria', $id)->get();
    }
}
