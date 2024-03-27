<?php

namespace App\Models;

class Roles
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre',
    ];

    public function getNombre(): string
    {
        return $this->nombre;
    }
}
