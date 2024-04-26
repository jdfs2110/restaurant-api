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
     * @param int $pagina Número de página que se desea obtener
     * @throws NoContentException cuando la página está vacía
     * @return Collection Los roles de la página deseada
     */
    public function paginated(int $pagina): Collection
    {
        $roles = $this->repository->all()->forPage($pagina, self::PAGINATION_LIMIT);

        if ($roles->isEmpty()) {
            throw new NoContentException('No hay roles.');
        }

        return $roles;
    }

    /**
     * @return int La cantidad de páginas que tienen los roles
     */
    public function getAmountOfPages(): int
    {
        $paginas = $this->repository->all()->count();

        return ceil($paginas / self::PAGINATION_LIMIT);
    }
}
