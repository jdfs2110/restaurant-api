<?php

namespace App\Http\Resources;

use App\Traits\GeneralClass;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoriaResource extends JsonResource
{
    use GeneralClass;
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
