<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PedidoResource extends JsonResource
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
            'fecha' => $this->getFecha(),
            'estado' => $this->getEstado(),
            'precio' => $this->getPrecio(),
            'numero_comensales' => $this->getNumeroComensales(),
            'id_mesa' => $this->getIdMesa(),
            'id_usuario' => $this->getIdUsuario()
        ];
    }
}
