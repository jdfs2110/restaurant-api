<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LineaCocinaDeletedEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public int $id)
    {
    }

    public function broadcastOn()
    {
        return ['lineas-cocina'];
    }

    public function broadcastAs()
    {
        return 'linea-deleted';
    }
}
