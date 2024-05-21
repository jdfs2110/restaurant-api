<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LineaCompletedEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public int $id, public string $message, public string $ocurredOn)
    {
    }

    public function broadcastOn()
    {
        return ['lineas-notifications'];
    }

    public function broadcastAs()
    {
        return 'linea-completed';
    }
}
