<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UsuarioResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return[
            'id' => $this->getId(),
            'name' => $this->getName(),
            'email' => $this->getEmail(),
            'estado' => $this->getEstado(),
            'fecha_ingreso' => $this->getFechaIngreso(),
            'id_rol' => $this->getIdRol(),
            'rol' => $this->getRol(),
            'created_at' => $this->getCreatedAt(),
            'deleted_at' => $this->getDeletedAt()
        ];
    }
}
