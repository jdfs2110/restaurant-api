<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
class Stock
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_producto',
        'cantidad',
    ];

    public function getIdProducto(): string
    {
        return $this->id_producto;
    }

    public function getCantidad(): int
    {
        return $this->cantidad;
    }
}