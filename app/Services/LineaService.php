<?php

namespace App\Services;

use App\Exceptions\NoContentException;
use App\Repositories\LineaRepository;
use Illuminate\Database\Eloquent\Collection;

class LineaService
{
    public function __construct(
        public readonly LineaRepository $repository
    )
    {
    }

    /**
     * @throws NoContentException
     */
    public function all(): Collection
    {
        $lineas = $this->repository->all();

        if ($lineas->isEmpty()) {
            throw new NoContentException('No hay lineas.');
        }

        return $lineas;
    }
}
