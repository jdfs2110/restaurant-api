<?php

namespace App\Services;

use App\Exceptions\NoContentException;
use App\Repositories\CategoriaRepository;
use Illuminate\Database\Eloquent\Collection;

class CategoriaService
{
    public function __construct(
        public readonly CategoriaRepository $repository
    )
    {
    }

    private const PAGINATION_LIMIT = 10;
    /**
     * @throws NoContentException
     */
    public function paginated(int $pagina): Collection
    {
        $categorias = $this->repository->all()->forPage($pagina, self::PAGINATION_LIMIT);

        if ($categorias->isEmpty()) {
            throw new NoContentException('No hay categorias.');
        }

        return $categorias;
    }

    public function getAmountOfPages(): int
    {
        $paginas = $this->repository->all()->count();

        return ceil($paginas / self::PAGINATION_LIMIT);
    }
}
