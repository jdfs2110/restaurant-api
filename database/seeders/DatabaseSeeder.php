<?php

namespace Database\Seeders;

use App\Models\Role;
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
            Role::factory()->create([
                'nombre' => $role
            ]);
        }

        User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@email.com',
            'password' => bcrypt('123456'),
            'id_rol' => '4',
            'fecha_ingreso' => date('Y-m-d')
        ]);

        User::factory()->create([
            'name' => 'cocinero',
            'email' => 'juancocina@email.com',
            'password' => bcrypt('123456'),
            'id_rol' => '2',
            'fecha_ingreso' => date('Y-m-d')
        ]);

        User::factory()->create([
            'name' => 'mesero',
            'email' => 'mesero1@email.com',
            'password' => bcrypt('123456'),
            'id_rol' => '1',
            'fecha_ingreso' => date('Y-m-d')
        ]);

        User::factory()->create([
            'name' => 'Departamento de Recursos Humanos',
            'email' => 'rrhh@email.com',
            'password' => bcrypt('123456'),
            'id_rol' => '3',
            'fecha_ingreso' => date('Y-m-d')
        ]);
    }
}
