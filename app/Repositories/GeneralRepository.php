<?php

namespace App\Repositories;

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
     * @throws Exception when model is not found
     */
    public function findOrFail($id): Model
    {
        $entity =  $this->builder->find($id);

        if (is_null($entity)) {
            throw new Exception($this->entityName . ' not found.');
        }

        return $entity;
    }

    public function create(array $data): Model
    {
        return $this->builder->create($data);
    }
}
