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
     * @param int $pagina Número de página que se desea obtener
     * @throws NoContentException cuuando la página está vacía
     * @return Collection Los productos de la página deseada
     */
    public function paginated(int $pagina): Collection
    {
        $productos = $this->repository->all()->forPage($pagina, self::PAGINATION_LIMIT);

        if ($productos->isEmpty()) {
            throw new NoContentException('No hay productos.');
        }

        return $productos;
    }

    /**
     * @return int La cantidad de productos existentes en la Base de Datos
     */
    public function getAmountOfProducts(): int
    {
        return $this->repository->all()->count();
    }

    /**
     * @return int El límite de productos por cada petición
     */
    public function getPaginationLimit(): int
    {
        return self::PAGINATION_LIMIT;
    }

    /**
     * @param int $id ID de la categoría
     * @throws NoContentException cuando la categoría no tiene productos
     * @return Collection Los productos de la categoría seleccionada
     */
    public function findAllByIdCategoria(int $id): Collection
    {
        $productos = $this->repository->findAllByIdCategoria($id);

        if ($productos->isEmpty()) {
            throw new NoContentException('No hay productos.');
        }

        return $productos;
    }
}
