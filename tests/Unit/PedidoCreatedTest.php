<?php

namespace Tests\Unit;

use App\Repositories\PedidoRepository;
use App\Services\MesaService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PedidoCreatedTest extends TestCase
{
//    use RefreshDatabase;

    protected $pedidoRepository;
    protected $mesaService;

    protected function setUp(): void
    {
        parent::setUp();
//        $this->seed();
        $this->pedidoRepository = $this->app->make(PedidoRepository::class);
        $this->mesaService = $this->app->make(MesaService::class);
    }

    public function test_pedido_created(): void
    {
        $mesa = $this->mesaService->checkIfBusy(1);

        $pedido = $this->pedidoRepository->create([
           'fecha' => now(),
           'estado' => 0,
           'precio' => 0,
           'numero_comensales' => 1,
           'id_mesa' => 1,
           'id_usuario' => 6
        ]);

        $this->mesaService->setOcupada($mesa);

        $this->assertModelExists($pedido);
    }
}
