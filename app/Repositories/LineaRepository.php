<?php

namespace App\Repositories;

use App\Models\Linea;

class LineaRepository extends GeneralRepository
{
    private const ENTITY_NAME = 'Línea';
    public function __construct()
    {
        $this->setBuilderFromModel(Linea::query()->getModel());
        $this->setEntityName(self::ENTITY_NAME);
    }
}
