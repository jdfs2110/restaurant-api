<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository extends GeneralRepository
{
    private const ENTITY_NAME = 'Usuario';
    public function __construct()
    {
        $this->setBuilderFromModel(User::query()->getModel());
        $this->setEntityName(self::ENTITY_NAME);
    }
}
