<?php

namespace App\Services;

use App\Exceptions\NoContentException;
use App\Repositories\RoleRepository;
use Illuminate\Database\Eloquent\Collection;

class RoleService
{
    public function __construct(
        public readonly RoleRepository $repository
    )
    {
    }

    /**
     * @throws NoContentException cuando la lista de roles está vacía
     * @return Collection la lista de roles
     */
    public function all(): Collection
    {
        $roles = $this->repository->all();

        if ($roles->isEmpty()) {
            throw new NoContentException();
        }

        return $roles;
    }
}
