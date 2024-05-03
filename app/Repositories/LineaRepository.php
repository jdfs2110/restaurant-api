<?php

namespace App\Repositories;

use App\Exceptions\NoContentException;
use App\Models\Linea;
use Illuminate\Database\Eloquent\Collection;

class LineaRepository extends GeneralRepository
{
    private const ENTITY_NAME = 'Línea';
    public function __construct()
    {
        $this->setBuilderFromModel(Linea::query()->getModel());
        $this->setNotFoundMessage(self::ENTITY_NAME . ' no encontrada.');
    }

    public function all(): Collection
    {
        return $this->getBuilder()->with(['producto'])->get();
    }

    public function findAllByIdPedido(int $id): Collection
    {
        return $this->getBuilder()->where('id_pedido', $id)->get();
    }

    /**
     * @throws NoContentException
     */
    public function getLineasOfCocina(): Collection
    {
        $lineas = $this->getBuilder()->with(['producto'])->where('tipo', 'cocina')->where('estado', '0')->get();

        if ($lineas->isEmpty()) {
            throw new NoContentException();
        }

        return $lineas;
    }

    /**
     * @throws NoContentException
     */
    public function getLineasOfBarra(): Collection
    {
        $lineas = $this->getBuilder()->with(['producto'])->where('tipo', 'barra')->where('estado', '0')->get();

        if ($lineas->isEmpty()) {
            throw new NoContentException();
        }

        return $lineas;
    }
}
