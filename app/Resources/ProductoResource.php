<?php

namespace App\Resources;

use App\Traits\CloudflareUtilsTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductoResource extends JsonResource
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
            'nombre' => $this->getNombre(),
            'precio' => $this->getPrecio(),
            'activo' => $this->getActivo(),
            'foto' => $this->toCloudflareUrl($this->getFoto()),
            'id_categoria' => $this->getIdCategoria(),
            'categoria' => $this->getCategoria(),
            'cantidad' => $this->getCantidad()
        ];
    }
}
