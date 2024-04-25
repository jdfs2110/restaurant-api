<?php

namespace App\Services;

use App\Exceptions\MesaDesocupadaException;
use App\Exceptions\ModelNotFoundException;
use App\Exceptions\NoContentException;
use App\Models\Pedido;
use App\Repositories\MesaRepository;
use App\Repositories\PedidoRepository;
use Illuminate\Database\Eloquent\Collection;

class MesaService
{
    public function __construct(
        public readonly MesaRepository   $repository,
        public readonly PedidoRepository $pedidoRepository,
    )
    {
    }

    public function all(): Collection
    {
        $mesas = $this->repository->all();

        if ($mesas->isEmpty()) {
            throw new NoContentException('No hay mesas.');
        }

        return $mesas;
    }

    /**
     * @throws ModelNotFoundException
     * @throws NoContentException
     */
    public function getPedidosByMesa(int $id): Collection
    {
        $this->repository->findOrFail($id);

        $pedidos = $this->pedidoRepository->findPedidosByIdMesa($id);

        if ($pedidos->isEmpty()) {
            throw new NoContentException('No hay pedidos asociados a esta mesa.');
        }

        return $pedidos;
    }

    /**
     * @throws ModelNotFoundException
     * @throws MesaDesocupadaException
     */
    public function getPedidoActual(int $id): Pedido
    {
        $mesa = $this->repository->findOrFail($id);

        if ($mesa->getEstado() !== 'ocupada') {
            throw new MesaDesocupadaException('La mesa no está ocupada.');
        }

        return $this->pedidoRepository->findLastPedidoByIdMesa($id);
    }
}
