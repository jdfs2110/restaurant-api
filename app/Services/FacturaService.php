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
     * @param int $pagina Número de página que se desea obtener
     * @throws NoContentException cuando la página está vacía
     * @return Collection Las facturas de la página deseada
     */
    public function paginated(int $pagina): Collection
    {
        $facturas = $this->repository->all()->forPage($pagina, self::PAGINATION_LIMIT);

        if ($facturas->isEmpty()) {
            throw new NoContentException('No hay facturas.');
        }

        return $facturas;
    }

    /**
     * @return int La cantidad de facturas existentes en la Base de Datos
     */
    public function getAmountOfFacturas(): int
    {
        return $this->repository->all()->count();
    }

    /**
     * @return int El limite de facturas por cada petición
     */
    public function getPaginationLimit(): int
    {
        return self::PAGINATION_LIMIT;
    }

}
