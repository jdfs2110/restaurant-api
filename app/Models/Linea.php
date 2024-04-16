<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Linea extends Model
{
    use SoftDeletes, HasFactory;

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

    public function getId(): int
    {
        return $this->id;
    }

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

    public function getCreatedAt(): string | null
    {
        return $this->created_at;
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'id_producto', 'id')->withTrashed();
    }
}
