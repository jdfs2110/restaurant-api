<?php

namespace Database\Factories;

use App\Models\Linea;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Linea>
 */
class LineaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'precio' => fake()->randomNumber(),
            'cantidad' => fake()->randomNumber(),
            'id_producto' => fake()->randomNumber(),
            'id_pedido' => fake()->randomNumber(),
        ];
    }
}
