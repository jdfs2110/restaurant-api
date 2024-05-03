<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LineaBarraDeletedEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public int $id)
    {
    }

    public function broadcastOn()
    {
        return ['lineas-barra'];
    }

    public function broadcastAs()
    {
        return 'linea-deleted';
    }
}
