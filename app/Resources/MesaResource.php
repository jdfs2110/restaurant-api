<?php

namespace App\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MesaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->getId(),
            'capacidad_maxima' => $this->getCapacidadMaxima(),
            'estado' => $this->getEstado(),
            'estado_numero' => $this->getEstadoValue(),
        ];
    }
}
