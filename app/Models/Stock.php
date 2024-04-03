<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Stock extends Model
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

    protected $table = 'stock';

    public function getId(): int
    {
        return $this->id;
    }

    public function getIdProducto(): string
    {
        return $this->id_producto;
    }

    public function getCantidad(): int
    {
        return $this->cantidad;
    }

    public function getCreatedAt(): string | null
    {
        return $this->created_at;
    }
}
