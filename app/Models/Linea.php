<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Linea
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'precio',
        'cantidad',
        'id_producto',
        'id_pedido'
    ];

    public function getPrecio(): float
    {
        return $this->precio;
    }

    public function getCantidad(): int
    {
        return $this->cantidad;
    }

    public function getIdProducto(): int
    {
        return $this->id_producto;
    }

    public function getIdPedido(): int
    {
        return $this->id_pedido;
    }
}
