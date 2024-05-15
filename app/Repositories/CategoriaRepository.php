<?php

namespace App\Repositories;

use App\Models\Categoria;
use Illuminate\Database\Eloquent\Collection;

class CategoriaRepository extends GeneralRepository
{
    private const ENTITY_NAME = 'Categoría';
    public function __construct()
    {
        $this->setBuilderFromModel(Categoria::query()->getModel());
        $this->setNotFoundMessage(self::ENTITY_NAME . ' no encontrada.');
    }

    public function findSimilarCategoriesByName(string $name): Collection
    {
        return $this->getBuilder()->where('nombre', $name)
            ->orWhere('nombre', 'like', "%$name%")->get();
    }
}
