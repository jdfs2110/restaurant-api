<?php

namespace App\Services;

use App\Exceptions\NoContentException;
use App\Repositories\FacturaRepository;
use Illuminate\Database\Eloquent\Collection;

class FacturaService
{
    public function __construct(
        public readonly FacturaRepository $repository
    )
    {
    }

    /**
     * @throws NoContentException
     */
    public function all(): Collection
    {
        $facturas = $this->repository->all();

        if ($facturas->isEmpty()) {
            throw new NoContentException('No hay facturas.');
        }

        return $facturas;
    }
}
