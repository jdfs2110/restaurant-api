<?php

namespace App\Services;

use App\Repositories\StockRepository;
use Exception;

class StockService
{
    public function __construct(
        public readonly StockRepository $repository
    )
    {
    }

    public function addStock($productId, int $quantity = 1): void
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
     */
    public function reduceStock($productId, int $quantity = 1): void
    {
        $stock = $this->repository->findByIdProductoOrFail($productId);

        $stock->cantidad -= $quantity;
        $stock->save();
    }

    public function setStock($productId, int $quantity = 1): void
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
}
