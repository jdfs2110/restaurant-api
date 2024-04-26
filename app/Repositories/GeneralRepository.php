<?php

namespace App\Repositories;

use App\Exceptions\ModelNotFoundException;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

abstract class GeneralRepository
{
    private ?Builder $builder = null;
    private string $entityName = '';
    protected function getBuilder(): Builder
    {
        return $this->builder;
    }
    protected function setBuilderFromModel(Model $model): void
    {
        $this->builder = $model::query();
    }

    protected function setEntityName(string $entityName): void
    {
        $this->entityName = $entityName;
    }

    public function all(): Collection
    {
        return $this->builder->get();
    }

    /**
     * @param int $id
     * @throws ModelNotFoundException cuando no se encuentra el modelo
     * @return Model El modelo que se ha encontrado
     */
    public function findOrFail(int $id): Model
    {
        $entity =  $this->builder->find($id);

        if (is_null($entity)) {
            throw new ModelNotFoundException($this->entityName . ' not found.');
        }

        return $entity;
    }

    public function create(array $data): Model
    {
        return $this->builder->create($data);
    }

    public function delete(Model $model): bool
    {
        return $model->delete();
    }
}
