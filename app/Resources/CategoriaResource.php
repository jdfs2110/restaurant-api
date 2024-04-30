<?php

namespace App\Resources;

use App\Traits\CloudflareUtilsTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoriaResource extends JsonResource
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
            'foto' => $this->toCloudflareUrl($this->getFoto()),
        ];
    }
}
