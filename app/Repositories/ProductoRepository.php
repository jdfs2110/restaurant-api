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
        $this->setNotFoundMessage(self::ENTITY_NAME . ' no encontrado.');
    }

    public function all(): Collection
    {
        return $this->getBuilder()->with(['categoria'])->get();
    }

    public function findAllByIdCategoria(int $id): Collection
    {
        return $this->getBuilder()->where('id_categoria', $id)->get();
    }

    public function findSimilarProductsByName(string $name): Collection
    {
        return $this->getBuilder()->where('nombre', $name)
            ->orWhere('nombre', 'like', "%$name%")->get();
    }
}
