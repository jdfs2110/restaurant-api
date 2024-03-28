<?php

namespace Database\Seeders;

use App\Models\Roles;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        /* User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]); */

        $roles = ['mesero', 'cocina', 'rrhh', 'admin'];

        foreach($roles as $role) {
            Roles::factory()->create([
                'nombre' => $role
            ]);
        }

        User::factory()->create([
            'name' => 'test',
            'email' => 'test@test.com',
            'password' => bcrypt('123456'),
            'id_rol' => '4',
            'fecha_ingreso' => date('Y-m-d')
        ]);
    }
}
