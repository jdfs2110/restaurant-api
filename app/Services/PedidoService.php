<?php

namespace App\Services;

use App\Exceptions\ModelNotFoundException;
use App\Exceptions\PedidoAlreadyServedException;
use App\Repositories\LineaRepository;
use App\Repositories\PedidoRepository;
use Exception;

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
}
