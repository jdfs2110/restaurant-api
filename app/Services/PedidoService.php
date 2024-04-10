<?php

namespace App\Services;

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
     * @throws Exception
     */
    public function recalculatePrice(int $id): void
    {
        $pedido = $this->repository->findOrFail($id);

        if ($pedido->isServido()) {
            throw new Exception('No se puede editar un pedido servido.');
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
