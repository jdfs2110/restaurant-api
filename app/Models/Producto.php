<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Producto extends Model
{
    use SoftDeletes, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre',
        'precio',
        'activo',
        'foto',
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

    public function setNombre(string $nombre): void
    {
        $this->nombre = $nombre;
    }

    public function getPrecio(): float
    {
        return $this->precio;
    }

    public function setPrecio(float $precio): void
    {
        $this->precio = $precio;
    }

    public function getActivo(): bool
    {
        return $this->activo;
    }

    public function setActivo(bool $activo): void
    {
        $this->activo = $activo;
    }

    public function getFoto(): string
    {
        return $this->foto;
    }

    public function setFoto(string $foto): void
    {
        $this->foto = $foto;
    }

    public function getIdCategoria(): int
    {
        return $this->id_categoria;
    }

    public function setIdCategoria(int $id_categoria): void
    {
        $this->id_categoria = $id_categoria;
    }

    public function getCreatedAt(): string|null
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): string|null
    {
        return $this->updated_at;
    }

    public function getDeletedAt(): string|null
    {
        return $this->deleted_at;
    }

//    public function categoria(): BelongsTo
//    {
//        return $this->belongsTo(Categoria::class, 'id_categoria', 'id')->withTrashed();
//    }
    public function getCategoria(): string
    {
        return $this->categoria;
    }

    public function getCantidad(): string
    {
        return $this->cantidad;
    }
}
