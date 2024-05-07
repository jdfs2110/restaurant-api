<?php

namespace App\Resources;

use App\Traits\CloudflareUtilsTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LineaResource extends JsonResource
{
    use CloudflareUtilsTrait;
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
            'producto' => $this->producto->getNombre(),
            'producto_foto' => $this->toCloudflareUrl($this->producto->getFoto()),
            'id_pedido' => $this->getIdPedido(),
            'tipo' => $this->getTipo(),
            'estado' => $this->getEstado(),
            'estado_numero' => $this->getEstadoValue()
        ];
    }
}
