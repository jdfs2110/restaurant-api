<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class UserRepository extends GeneralRepository
{
    private const ENTITY_NAME = 'Usuario';
    public function __construct()
    {
        $this->setBuilderFromModel(User::query()->getModel());
        $this->setEntityName(self::ENTITY_NAME);
    }

    public function findByEmail(string $email): Model | null
    {
        return $this->getBuilder()->where('email', $email)->get()->first();
    }

    public function findAllByIdRol(int $id): Collection
    {
        return $this->getBuilder()->where('id_rol', $id)->get();
    }
}
