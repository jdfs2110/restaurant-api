<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pedido extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'fecha',
        'estado',
        'precio',
        'numero_comensales',
        'id_mesa',
        'id_usuario'
    ];

    private const ESTADOS = [
        'pendiente',
        'preparando',
        'servido',
        'cancelado'
    ];

    public function getId(): int
    {
        return $this->id;
    }
    public function getFecha(): string
    {
        return $this->fecha;
    }

    public function getEstado(): string
    {
        return self::ESTADOS[$this->estado];
    }

    public function getPrecio(): float
    {
        return $this->precio;
    }

    public function getNumeroComensales(): int
    {
        return $this->numero_comensales;
    }

    public function getIdMesa(): int
    {
        return $this->id_mesa;
    }

    public function getIdUsuario(): int
    {
        return $this->id_usuario;
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
