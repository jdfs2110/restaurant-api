<?php

namespace Database\Factories;

use App\Models\Pedido;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Pedido>
 */
class PedidoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'fecha' => fake()->date('Y-m-d'),
            'estado' => fake()->randomNumber(),
            'precio' => fake()->randomFloat(2),
            'numero_comensales' => fake()->randomNumber(),
            'id_mesa' => fake()->randomNumber(),
            'id_usuario' => fake()->randomNumber()
        ];
    }
}
