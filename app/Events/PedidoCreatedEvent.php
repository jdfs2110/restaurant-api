<?php

namespace App\Events;

use App\Http\Resources\PedidoResource;
use App\Models\Pedido;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PedidoCreatedEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var Pedido
     */
    public $pedido;

    public function __construct(Pedido $pedido)
    {
        $this->pedido = new PedidoResource($pedido);
    }

    public function broadcastOn()
    {
        return ['pedido-created'];
    }

    public function broadcastAs(): string{
        return 'pedido-created';
    }
}
