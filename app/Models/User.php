<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'estado',
        'fecha_ingreso',
        'id_rol'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getFechaIngreso(): string
    {
        return $this->fecha_ingreso;
    }

    public function getEstado(): bool
    {
        return $this->estado;
    }

    private const ROLES = [ // Descartar array si se permiten crear / borrar roles en un futuro.
        1 => 'mesero',
        2 => 'cocina',
        3 => 'rrhh',
        4 => 'admin',
        5 => 'bloqueado'
    ];

    public function getIdRol(): int
    {
        return $this->id_rol;
    }

    public function getRol(): string
    {
        return self::ROLES[$this->id_rol];
    }

    public function getCreatedAt(): string | null
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): string | null
    {
        return $this->updated_at;
    }

    public function getDeletedAt(): string | null
    {
        return $this->deleted_at;
    }
}
