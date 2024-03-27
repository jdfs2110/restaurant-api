<?php

namespace App\Models;

class Mesa
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'capacidad_maxima',
        'estado',
    ];

    private const ESTADOS = [
        'libre',
        'ocupada',
        'reservada'
    ];

    public function getCapacidadMaxima(): int
    {
        return $this->capacidad_maxima;
    }
    public function getEstado(): string
    {
        return self::ESTADOS[$this->estado];
    }
}
