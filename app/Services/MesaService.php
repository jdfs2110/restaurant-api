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

    /**
     * @throws NoContentException cuando la lista de mesas está vacía
     * @return Collection Todas las mesas
     */
    public function all(): Collection
    {
        $mesas = $this->repository->all();

        if ($mesas->isEmpty()) {
            throw new NoContentException('No hay mesas.');
        }

        return $mesas;
    }

    /**
     * @param int $id ID de la Mesa
     * @throws ModelNotFoundException cuando no se encuentra la mesa
     * @throws NoContentException cuando la mesa no tiene pedidos
     * @return Collection Los pedidos de esa mesa
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
     * @param int $id ID de la mesa
     * @throws ModelNotFoundException cuando no se encuentra la mesa
     * @throws MesaDesocupadaException cuando la mesa no tiene el estado 'ocupada'
     * @return Pedido el último pedido de la mesa
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
