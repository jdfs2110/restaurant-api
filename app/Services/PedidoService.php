<?php

namespace App\Services;

use App\Exceptions\ModelNotFoundException;
use App\Exceptions\NoContentException;
use App\Exceptions\PedidoAlreadyServedException;
use App\Repositories\LineaRepository;
use App\Repositories\PedidoRepository;
use Exception;
use Illuminate\Database\Eloquent\Collection;

class PedidoService
{
    public function __construct(
        public readonly PedidoRepository $repository,
        public readonly LineaRepository $lineaRepository
    )
    {
    }

    /**
     * @throws PedidoAlreadyServedException si el pedido a recalcular ya está servido
     * @throws ModelNotFoundException when pedido is not found
     */
    public function recalculatePrice(int $id): void
    {
        $pedido = $this->repository->findOrFail($id);

        if ($pedido->isServido()) {
            throw new PedidoAlreadyServedException('No se puede editar un pedido servido.');
        }

        $lineas = $this->lineaRepository->findAllByIdPedido($id);

        $sum = $lineas->map(function($linea) {
           $linea->precioTotal = $linea->cantidad * $linea->precio;
           return $linea;
        })->sum('precioTotal');

        $pedido->precio = $sum;

        $pedido->save();
    }

    private const PAGINATION_LIMIT = 15;
    /**
     * @throws NoContentException
     */
    public function paginated(int $pagina): Collection
    {
        $pedidos = $this->repository->all()->forPage($pagina, self::PAGINATION_LIMIT);

        if ($pedidos->isEmpty()) {
            throw new NoContentException('No hay pedidos.');
        }

        return $pedidos;
    }

    public function getAmountOfPages(): int
    {
        $paginas = $this->repository->all()->count();

        return ceil($paginas / self::PAGINATION_LIMIT);
    }

    /**
     * @throws NoContentException
     */
    public function findPedidosByIdUsuario(int $id): Collection
    {
        $pedidos = $this->repository->findPedidosByIdUsuario($id);

        if ($pedidos->isEmpty()) {
            throw new NoContentException('No hay pedidos.');
        }

        return $pedidos;
    }
}
