<?php

namespace App\Models;

use DateTime;

class Pedido
{
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
        'id_empleado'
    ];

    private const ESTADOS = [
        'pendiente',
        'preparando',
        'servido',
        'cancelado'
    ];

    public function getFecha(): DateTime
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

    public function getIdEmpleado(): int
    {
        return $this->id_empleado;
    }
}
