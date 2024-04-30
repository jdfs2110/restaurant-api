<?php

namespace App\Repositories;

use App\Models\Role;

class RoleRepository extends GeneralRepository
{
    private const ENTITY_NAME = 'Rol';
    public function __construct()
    {
        $this->setBuilderFromModel(Role::query()->getModel());
        $this->setNotFoundMessage(self::ENTITY_NAME . ' no encontrado.');
    }
}
