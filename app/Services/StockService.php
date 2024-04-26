<?php

namespace App\Services;

use App\Exceptions\NegativeQuantityException;
use App\Exceptions\NoContentException;
use App\Repositories\StockRepository;
use Exception;
use Illuminate\Database\Eloquent\Collection;

class StockService
{
    public function __construct(
        public readonly StockRepository $repository
    )
    {
    }

    public function addStock(int $productId, int $quantity = 1): void
    {
        $stock = $this->repository->findByIdProducto($productId);

        if (is_null($stock)) {
            $this->repository->create([
                'cantidad' => $quantity,
                'id_producto' => $productId
            ]);

            return;
        }

        $stock->cantidad += $quantity;
        $stock->save();
    }

    /**
     * @throws Exception
     * @throws NegativeQuantityException
     */
    public function reduceStock(int $productId, int $quantity = 1): void
    {
        $stock = $this->repository->findByIdProductoOrFail($productId);

        $stock->cantidad -= $quantity;

        if ($stock->cantidad < 0) {
            throw new NegativeQuantityException('La cantidad de stock es negativa');
        }

        $stock->save();
    }

    public function setStock(int $productId, int $quantity = 1): void
    {
        $stock = $this->repository->findByIdProducto($productId);

        if (is_null($stock)) {
            $this->repository->create([
                'cantidad' => $quantity,
                'id_producto' => $productId
            ]);

            return;
        }

        $stock->cantidad = $quantity;
        $stock->save();
    }

    /**
     * @throws Exception when the Stock is not found (shouldn't happen)
     * @throws NegativeQuantityException
     */
    public function updateStock(int $productId, int $firstQuantity, int $secondQuantity): void
    {
        $this->repository->findByIdProductoOrFail($productId);

        if ($firstQuantity < $secondQuantity) {
            $this->addStock($productId, ($secondQuantity - $firstQuantity));

            return;
        }

        $this->reduceStock($productId, ($firstQuantity - $secondQuantity));
    }

    private const PAGINATION_LIMIT = 10;
    /**
     * @throws NoContentException
     */
    public function paginated(int $pagina): Collection
    {
        $stockList = $this->repository->all()->forPage($pagina, self::PAGINATION_LIMIT);

        if ($stockList->isEmpty()) {
            throw new NoContentException('No hay stock disponible');
        }

        return $stockList;
    }

    public function getAmountOfPages(): int
    {
        $paginas = $this->repository->all()->count();

        return ceil($paginas / self::PAGINATION_LIMIT);
    }
}
