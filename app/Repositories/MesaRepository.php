<?php

namespace App\Repositories;

use App\Models\Mesa;

class MesaRepository extends GeneralRepository
{
    private const ENTITY_NAME = 'Mesa';
    public function __construct()
    {
        $this->setBuilderFromModel(Mesa::query()->getModel());
        $this->setEntityName(self::ENTITY_NAME);
    }
}
