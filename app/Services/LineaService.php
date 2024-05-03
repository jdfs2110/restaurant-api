<?php

namespace App\Services;

use App\Exceptions\LineaAlreadyCompletedException;
use App\Exceptions\ModelNotFoundException;
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
     * @param int $pagina Número de página que se desea obtener
     * @throws NoContentException cuando la página está vacía
     * @return Collection Las líneas de la página deseada
     */
    public function paginated(int $pagina): Collection
    {
        $lineas = $this->repository->all()->forPage($pagina, self::PAGINATION_LIMIT);

        if ($lineas->isEmpty()) {
            throw new NoContentException('No hay lineas.');
        }

        return $lineas;
    }

    /**
     * @return int La cantidad de páginas que tienen las líneas
     */
    public function  getAmountOfPages(): int
    {
        $paginas = $this->repository->all()->count();

        return ceil($paginas / self::PAGINATION_LIMIT);
    }

    /**
     * @param int $id ID del pedido
     * @throws NoContentException cuando no hay líneas en el pedido
     * @return Collection Las líneas del pedido
     */
    public function findAllByIdPedido(int $id): Collection
    {
        $lineas = $this->repository->findAllByIdPedido($id);

        if ($lineas->isEmpty()) {
            throw new NoContentException('No hay lineas.');
        }

        return $lineas;
    }

    /**
     * @param int $id ID de la línea
     * @throws ModelNotFoundException cuando la línea no existe
     * @throws LineaAlreadyCompletedException cuando la línea ya está completada
     */
    public function completarLinea(int $id): void
    {
        $linea = $this->repository->findOrFail($id);

        if ($linea->getEstadoValue() == 1) {
            throw new LineaAlreadyCompletedException('Esta línea ya está completada.');
        }

        $linea->setEstado(1);
        $linea->save();
    }
}
