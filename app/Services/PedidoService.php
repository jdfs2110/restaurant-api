<?php

namespace App\Services;

use App\Exceptions\ModelNotFoundException;
use App\Exceptions\NoContentException;
use App\Exceptions\PedidoAlreadyServedException;
use App\Exceptions\UserIsNotWaiterException;
use App\Models\Pedido;
use App\Repositories\LineaRepository;
use App\Repositories\MesaRepository;
use App\Repositories\PedidoRepository;
use Illuminate\Database\Eloquent\Collection;

class PedidoService
{
    public function __construct(
        public readonly PedidoRepository $repository,
        public readonly LineaRepository $lineaRepository,
        public readonly UserService     $userService,
        public readonly MesaRepository  $mesaRepository,
        public readonly MesaService     $mesaService,
    )
    {
    }

    /**
     * @param Pedido $pedido El pedido a revisar
     * @throws PedidoAlreadyServedException cuando el pedido ya está servido
     */
    public function checkIfServido(Pedido $pedido): void
    {
        if ($pedido->isServido()) {
            throw new PedidoAlreadyServedException('No se puede editar un pedido servido.');
        }
    }

    /**
     * @param int $id ID del pedido
     * @throws PedidoAlreadyServedException si el pedido a recalcular ya está servido
     * @throws ModelNotFoundException cuando no se encuentra el pedido
     */
    public function recalculatePrice(int $id): void
    {
        $pedido = $this->repository->findOrFail($id);

        $this->checkIfServido($pedido);

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
     * @param int $pagina Número de página que se desea obtener
     * @throws NoContentException cuando la página esta vacía
     * @return Collection Los pedidos de la página deseada
     */
    public function paginated(int $pagina): Collection
    {
        $pedidos = $this->repository->all()->forPage($pagina, self::PAGINATION_LIMIT);

        if ($pedidos->isEmpty()) {
            throw new NoContentException('No hay pedidos.');
        }

        return $pedidos;
    }

    /**
     * @return int La cantidad de pedidos existentes en la Base de Datos
     */
    public function getAmountOfPedidos(): int
    {
        return $this->repository->all()->count();
    }

    /**
     * @return int El límite de pedidos por cada petición
     */
    public function getPaginationLimit(): int
    {
        return self::PAGINATION_LIMIT;
    }

    /**
     * @param int $id ID del usuario
     * @throws ModelNotFoundException cuando no se encuentra el usuario
     * @throws UserIsNotWaiterException cuando el usuario introducido no tiene el rol 'mesero'
     * @throws NoContentException cuando el usuario no tiene pedidos
     * @returns Collection Los pedidos manejados por el usuario
     */
    public function findPedidosByIdUsuario(int $id): Collection
    {
        $this->userService->checkIfMesero($id);

        $pedidos = $this->repository->findPedidosByIdUsuario($id);

        if ($pedidos->isEmpty()) {
            throw new NoContentException('No hay pedidos.');
        }

        return $pedidos;
    }

    /**
     * @param int $id ID del pedido
     * @throws ModelNotFoundException cuando no se encuentra el pedido o la mesa
     * @throws PedidoAlreadyServedException cuando el pedido ya está servido
     */
    public function servirPedido(int $id)
    {
        $pedido = $this->repository->findOrFail($id);

        $this->checkIfServido($pedido);

        $lineas = $this->lineaRepository->findAllByIdPedido($id);

        $lineas->some(function ($linea) {
            dd($linea->estado === 0);
        });

        $mesa = $this->mesaRepository->findOrFail($pedido->getIdMesa());

        $this->mesaService->setLibre($mesa);

        $pedido->setEstado(2);
        $pedido->save();

        return $this->mesaRepository->findOrFail($pedido->getIdMesa());
    }
}
