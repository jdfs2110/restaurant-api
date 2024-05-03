<?php

namespace App\Events;

use App\Models\Linea;
use App\Resources\LineaResource;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LineaCocinaCreatedEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var Linea
     */
    public $data;

    public function __construct(Linea $linea, public string $ocurredOn)
    {
        $this->data = new LineaResource($linea);
    }

    public function broadcastOn()
    {
        return ['lineas-cocina'];
    }

    public function broadcastAs()
    {
        return 'linea-created';
    }
}
