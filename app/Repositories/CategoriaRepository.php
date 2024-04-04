<?php

namespace App\Repositories;

use App\Models\Categoria;

class CategoriaRepository extends GeneralRepository
{
    private const ENTITY_NAME = 'Categoría';
    public function __construct()
    {
        $this->setBuilderFromModel(Categoria::query()->getModel());
        $this->setEntityName(self::ENTITY_NAME);
    }
}
