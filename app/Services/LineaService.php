<?php

namespace App\Services;

use App\Exceptions\NoContentException;
use App\Repositories\LineaRepository;
use Illuminate\Database\Eloquent\Collection;

class LineaService
{
    public function __construct(
        public readonly LineaRepository $repository
    )
    {
    }

    private const PAGINATION_LIMIT = 20;
    /**
     * @throws NoContentException
     */
    public function paginated(int $pagina): Collection
    {
        $lineas = $this->repository->all()->forPage($pagina, self::PAGINATION_LIMIT);

        if ($lineas->isEmpty()) {
            throw new NoContentException('No hay lineas.');
        }

        return $lineas;
    }

    public function  getAmountOfPages(): int
    {
        $paginas = $this->repository->all()->count();

        return ceil($paginas / self::PAGINATION_LIMIT);
    }

    /**
     * @throws NoContentException
     */
    public function findAllByIdPedido(int $id): Collection
    {
        $lineas = $this->repository->findAllByIdPedido($id);

        if ($lineas->isEmpty()) {
            throw new NoContentException('No hay lineas.');
        }

        return $lineas;
    }
}
