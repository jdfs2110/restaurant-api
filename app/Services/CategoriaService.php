<?php

namespace App\Services;

use App\Exceptions\NoContentException;
use App\Repositories\CategoriaRepository;
use Illuminate\Database\Eloquent\Collection;

class CategoriaService
{
    public function __construct(
        public readonly CategoriaRepository $repository
    )
    {
    }

    /**
     * @throws NoContentException
     */
    public function all(): Collection
    {
        $categorias = $this->repository->all();

        if ($categorias->isEmpty()) {
            throw new NoContentException('No hay categorias.');
        }

        return $categorias;
    }
}
