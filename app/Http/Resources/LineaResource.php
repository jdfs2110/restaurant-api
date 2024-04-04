<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LineaResource extends JsonResource
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
            'precio' => $this->getPrecio(),
            'cantidad' => $this->getCantidad(),
            'id_producto' => $this->getIdProducto(),
            'id_pedido' => $this->getIdPedido(),
            'created_at' => $this->getCreatedAt()
        ];
    }
}