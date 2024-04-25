<?php

namespace App\Services;

use App\Exceptions\NoContentException;
use App\Repositories\FacturaRepository;
use Illuminate\Database\Eloquent\Collection;

class FacturaService
{
    public function __construct(
        public readonly FacturaRepository $repository
    )
    {
    }

    private const PAGINATION_LIMIT = 20;
    /**
     * @throws NoContentException
     */
    public function paginated(int $pagina): Collection
    {
        $facturas = $this->repository->all()->forPage($pagina, self::PAGINATION_LIMIT);

        if ($facturas->isEmpty()) {
            throw new NoContentException('No hay facturas.');
        }

        return $facturas;
    }

    public function getAmountOfPages(): int
    {
        $paginas = $this->repository->all()->count();

        return ceil($paginas / self::PAGINATION_LIMIT);
    }
}
