<?php

namespace App\Services;

use App\Exceptions\NoContentException;
use App\Repositories\ProductoRepository;
use Illuminate\Database\Eloquent\Collection;

class ProductoService
{
    public function __construct(
        public readonly ProductoRepository $repository
    )
    {
    }

    /**
     * @throws NoContentException
     */
    public function all(): Collection
    {
        $productos = $this->repository->all();

        if ($productos->isEmpty()) {
            throw new NoContentException('No hay productos.');
        }

        return $productos;
    }
}
