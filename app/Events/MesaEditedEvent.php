<?php

namespace App\Events;

use App\Models\Mesa;
use App\Resources\MesaResource;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MesaEditedEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var Mesa
     */
    public $data;

    public function __construct(Mesa $mesa, public string $message)
    {
        $this->data = new MesaResource($mesa);
    }

    public function broadcastOn()
    {
        return ['mesas'];
    }

    public function broadcastAs()
    {
        return 'mesa-edited';
    }
}
