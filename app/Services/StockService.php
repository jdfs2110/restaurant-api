<?php

namespace App\Services;

use App\Exceptions\NegativeQuantityException;
use App\Exceptions\NoContentException;
use App\Models\Producto;
use App\Models\Stock;
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

    /**
     * @param int $productId ID del producto
     * @param int $quantity cantidad a añadir
     */
    public function addStock(int $productId, int $quantity = 1, bool $check = true): void
    {
        $stock = $this->repository->findByIdProducto($productId);

        if ($check && is_null($stock)) {
            $this->repository->create([
                'cantidad' => $quantity,
                'id_producto' => $productId
            ]);

            return;
        }

//        $stock->cantidad += $quantity;
//        $stock->save();
        Stock::query()->where('id_producto', $productId)->update(['cantidad' => $stock->getCantidad() + $quantity]);

    }

    /**
     * @param int $productId ID del producto
     * @param int $quantity cantidad a restar
     * @throws Exception cuando no se encuentra Stock (no debería de suceder...)
     * @throws NegativeQuantityException cuando el Stock es negativo despues de restarle la cantidad introducida
     */
    public function reduceStock(int $productId, int $quantity = 1): void
    {
        $stock = $this->repository->findByIdProductoOrFail($productId);

        $producto = Producto::query()->find($productId);

        $stock->cantidad -= $quantity;

        if ($stock->cantidad < 0) {
            throw new NegativeQuantityException('No hay suficiente stock');
        }

        if ($stock->cantidad == 0) {
            $producto->activo = false;
            $producto->save();
        }

        $stock->save();
    }

    /**
     * @param int $productId ID del producto
     * @param int $quantity Cantidad a actualizar (Por defecto 1)
     */
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
     * @param int $productId ID del producto
     * @param int $newQuantity Cantidad a actualizar
     * @param int $oldQuantity Cantidad actual
     * @throws Exception cuando no se encuentra Stock (no debería de suceder...)
     * @throws NegativeQuantityException cuando el Stock es negativo despues de restarle la cantidad introducida
     */
    public function updateStock(int $productId, int $newQuantity, int $oldQuantity): void
    {
        $this->repository->findByIdProductoOrFail($productId);

        if ($newQuantity < $oldQuantity) {
            $this->addStock($productId, ($oldQuantity - $newQuantity));

            return;
        }

        $this->reduceStock($productId, ($newQuantity - $oldQuantity));
    }

    private const PAGINATION_LIMIT = 10;

    /**
     * @param int $pagina Número de página que se desea obtener
     * @return Collection La lista de stock de la página deseada
     * @throws NoContentException cuando la página está vacía
     */
    public function paginated(int $pagina): Collection
    {
        $stockList = $this->repository->all()->forPage($pagina, self::PAGINATION_LIMIT);

        if ($stockList->isEmpty()) {
            throw new NoContentException('No hay stock disponible');
        }

        return $stockList;
    }

    /**
     * @return int La cántidad Stock existente en la Base de Datos
     */
    public function getAmountOfStock(): int
    {
        return $this->repository->all()->count();
    }

    /**
     * @return int El límite de stock por cada petición
     */
    public function getPaginationLimit(): int
    {
        return self::PAGINATION_LIMIT;
    }
}
