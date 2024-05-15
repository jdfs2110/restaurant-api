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
     * @param int $pagina Número de página que se desea obtener
     * @throws NoContentException cuando la página está vacía
     * @return Collection Las categorías de la página deseada
     */
    public function paginated(int $pagina): Collection
    {
        $categorias = $this->repository->all()->forPage($pagina, self::PAGINATION_LIMIT);

        if ($categorias->isEmpty()) {
            throw new NoContentException('No hay categorias.');
        }

        return $categorias;
    }

    /**
     * @return int la cantidad de categorias existentes en la Base de Datos
     */
    public function getAmountOfCategories(): int
    {
        return $this->repository->all()->count();
    }

    /**
     * @return int El límite de categorias por cada petición
     */
    public function getPaginationLimit(): int
    {
        return self::PAGINATION_LIMIT;
    }
}
