<?php

namespace App\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StockResource extends JsonResource
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
            'id_producto' => $this->getIdProducto(),
            'producto' => $this->producto->getNombre(),
            'cantidad' => $this->getCantidad(),
            'created_at' => $this->getCreatedAt()
        ];
    }
}
