<?php

namespace App\Repositories;

use App\Exceptions\EmailAlreadyInUseException;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class UserRepository extends GeneralRepository
{
    private const ENTITY_NAME = 'Usuario';

    public function __construct()
    {
        $this->setBuilderFromModel(User::query()->getModel());
        $this->setNotFoundMessage(self::ENTITY_NAME . ' no encontrado.');
    }

    public function all(): Collection
    {
        return $this->getBuilder()->with(['rol'])->get();
    }

    public function findByEmail(string $email): Model|null
    {
        return $this->getBuilder()->where('email', $email)->get()->first();
    }

    public function findAllByIdRol(int $id): Collection
    {
        return $this->getBuilder()->where('id_rol', $id)->get();
    }

    /**
     * @param int $id ID del usuario
     * @param string $email Email a comprobar
     * @throws EmailAlreadyInUseException cuando el email ya está registrado
     */
    public function emailExists(int $id, string $email): void
    {
        // DONT TOUCH que se rompe
        $count = $this->getBuilder()->getModel()
            ->where('id', '!=', $id)
            ->where('email', $email)
            ->where('deleted_at', 'is not', null)
            ->get()
            ->count();

        if ($count >= 1) {
            throw new EmailAlreadyInUseException('El email ingresado ya existe');
        }
    }

    public function findSimilarUsers(string $str): Collection
    {
        return $this->getBuilder()
            ->where('name', $str)
            ->orWhere('email', $str)
            ->orWhere('name', 'like', "%$str%")
            ->orWhere('email', 'like', "%$str%")
            ->get();
    }
}
