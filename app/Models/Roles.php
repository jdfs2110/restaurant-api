<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Roles
{
    use SoftDeletes;

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
