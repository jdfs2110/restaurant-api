<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Producto extends Model
{
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre',
        'precio',
        'activo',
        'id_categoria'
    ];

    public function getId(): int
    {
        return $this->id;
    }

    public function getNombre(): string
    {
        return $this->nombre;
    }

    public function getPrecio(): float
    {
        return $this->precio;
    }

    public function getActivo(): bool
    {
        return $this->activo;
    }

    public function getIdCategoria(): int
    {
        return $this->id_categoria;
    }

    public function getCreatedAt(): string | null
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): string | null
    {
        return $this->updated_at;
    }

    public function getDeletedAt(): string | null
    {
        return $this->deleted_at;
    }
}
