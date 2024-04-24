<?php

namespace App\Services;

use App\Exceptions\NoContentException;
use App\Repositories\MesaRepository;
use Illuminate\Database\Eloquent\Collection;

class MesaService
{
    public function __construct(
        public readonly MesaRepository $repository
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
}
