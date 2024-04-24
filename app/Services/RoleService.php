<?php

namespace App\Services;

use App\Exceptions\NoContentException;
use App\Repositories\RoleRepository;
use Illuminate\Database\Eloquent\Collection;

class RoleService
{
    public function __construct(
        public readonly RoleRepository $roleRepository
    )
    {
    }

    /**
     * @throws NoContentException
     */
    public function all(): Collection
    {
        $roles = $this->roleRepository->all();

        if ($roles->isEmpty()) {
            throw new NoContentException('No hay roles');
        }

        return $roles;
    }
}
