<?php

namespace App\Models;

use DateTime;

class Factura
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'fecha',
        'id_pedido'
    ];

    public function getFecha(): DateTime
    {
        return $this->fecha;
    }

    public function getIdPedido(): int
    {
        return $this->id_pedido;
    }
}
