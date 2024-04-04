<?php

namespace App\Repositories;

use App\Models\User;
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
        return User::query()->where('email', $email)->get()->first();
    }
}
