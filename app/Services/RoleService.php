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

    private const PAGINATION_LIMIT = 10;
    /**
     * @throws NoContentException
     */
    public function paginated(int $pagina): Collection
    {
        $roles = $this->repository->all()->forPage($pagina, self::PAGINATION_LIMIT);

        if ($roles->isEmpty()) {
            throw new NoContentException('No hay roles.');
        }

        return $roles;
    }

    public function getAmountOfPages(): int
    {
        $paginas = $this->repository->all()->count();

        return ceil($paginas / self::PAGINATION_LIMIT);
    }
}
