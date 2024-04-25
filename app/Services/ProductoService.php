<?php

namespace App\Services;

use App\Exceptions\NoContentException;
use App\Repositories\ProductoRepository;
use Illuminate\Database\Eloquent\Collection;

class ProductoService
{
    public function __construct(
        public readonly ProductoRepository $repository
    )
    {
    }

    private const PAGINATION_LIMIT = 10;
    /**
     * @throws NoContentException
     */
    public function paginated(int $pagina): Collection
    {
        $productos = $this->repository->all()->forPage($pagina, self::PAGINATION_LIMIT);

        if ($productos->isEmpty()) {
            throw new NoContentException('No hay productos.');
        }

        return $productos;
    }

    public function getAmountOfPages(): int
    {
        $paginas = $this->repository->all()->count();

        return ceil($paginas / self::PAGINATION_LIMIT);
    }
}
