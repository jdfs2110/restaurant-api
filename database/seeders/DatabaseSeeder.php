<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Factura;
use App\Models\Linea;
use App\Models\Mesa;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\Role;
use App\Models\Stock;
use App\Models\User;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    private const PRODUCT_QUANTITY = 14;
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        /**
         *  Creación de roles
         */
        $roles = ['mesero', 'cocina', 'rrhh', 'admin', 'bloqueado'];

        foreach ($roles as $role) {
            Role::factory()->create([
                'nombre' => $role
            ]);
        }

        /**
         *  Creación de usuarios
         */
        User::factory()->create([
            'name' => 'test user',
            'email' => 'test@test.com',
            'password' => bcrypt('123456'),
            'id_rol' => '4',
            'fecha_ingreso' => date('Y-m-d')
        ]);

        User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@jdfs.dev',
            'password' => bcrypt('123456'),
            'id_rol' => '4',
            'fecha_ingreso' => date('Y-m-d')
        ]);

        User::factory()->create([
            'name' => 'cocinero',
            'email' => 'juancocina@jdfs.dev',
            'password' => bcrypt('123456'),
            'id_rol' => '2',
            'fecha_ingreso' => date('Y-m-d')
        ]);

        User::factory()->create([
            'name' => 'cocinero 2',
            'email' => 'josecocina@jdfs.dev',
            'password' => bcrypt('123456'),
            'id_rol' => '2',
            'fecha_ingreso' => date('Y-m-d')
        ]);

        User::factory()->create([
            'name' => 'mesero',
            'email' => 'mesero1@jdfs.dev',
            'password' => bcrypt('123456'),
            'id_rol' => '1',
            'fecha_ingreso' => date('Y-m-d')
        ]);

        User::factory()->create([
            'name' => 'mesero 2',
            'email' => 'mesero2@jdfs.dev',
            'password' => bcrypt('123456'),
            'id_rol' => '1',
            'fecha_ingreso' => date('Y-m-d')
        ]);

        User::factory()->create([
            'name' => 'mesero 3',
            'email' => 'mesero3@jdfs.dev',
            'password' => bcrypt('123456'),
            'id_rol' => '1',
            'fecha_ingreso' => date('Y-m-d')
        ]);

        User::factory()->create([
            'name' => 'Departamento de Recursos Humanos',
            'email' => 'rrhh@jdfs.dev',
            'password' => bcrypt('123456'),
            'id_rol' => '3',
            'fecha_ingreso' => date('Y-m-d')
        ]);

        /**
         *  Creación de categorias
         */
        Categoria::factory()->create([
            'nombre' => 'Bebidas',
            'foto' => 'categorias/refresco.webp'
        ]);

        Categoria::factory()->create([
            'nombre' => 'Entrantes',
            'foto' => 'categorias/entrantes.webp'
        ]);

        Categoria::factory()->create([
            'nombre' => 'Principales',
            'foto' => 'categorias/principales.webp'
        ]);

        Categoria::factory()->create([
            'nombre' => 'Postres',
            'foto' => 'categorias/postres.webp'
        ]);

        /**
         *  Creación de productos
         *  Categoría 1 (Bebidas)
         */
        Producto::factory()->create([
            'id' => 1, // <-- Remove if not working
            'nombre' => 'Agua mineral',
            'precio' => 1.20,
            'activo' => true,
            'id_categoria' => 1,
            'foto' => 'productos/agua.webp'
        ]);

        Producto::factory()->create([
            'id' => 2,
            'nombre' => 'Coca-Cola',
            'precio' => 1.80,
            'activo' => true,
            'id_categoria' => 1,
            'foto' => 'productos/cocacola.webp'
        ]);

        Producto::factory()->create([
            'id' => 3,
            'nombre' => 'Cerveza',
            'precio' => 0.80,
            'activo' => true,
            'id_categoria' => 1,
            'foto' => 'productos/cerveza.webp'
        ]);

        Producto::factory()->create([
            'id' => 4,
            'nombre' => 'Fanta naranja',
            'precio' => 1.80,
            'activo' => true,
            'id_categoria' => 1,
            'foto' => 'productos/fanta_naranja.webp'
        ]);

        /**
         *  Creación de productos
         *  Categoría 2 (Entrantes)
         */
        Producto::factory()->create([
            'id' => 5,
            'nombre' => 'Ensalada César',
            'precio' => 4.50,
            'activo' => true,
            'id_categoria' => 2,
            'foto' => 'productos/ensalada_cesar.webp'
        ]);

        Producto::factory()->create([
            'id' => 6,
            'nombre' => 'Sopa de verduras',
            'precio' => 3.60,
            'activo' => true,
            'id_categoria' => 2,
            'foto' => 'productos/sopa_verduras.webp'
        ]);

        Producto::factory()->create([
            'id' => 7,
            'nombre' => 'Nachos con guacamole',
            'precio' => 5.00,
            'activo' => true,
            'id_categoria' => 2,
            'foto' => 'productos/nachos_guacamole.webp'
        ]);

        /**
         *  Creación de productos
         *  Categoría 3 (Principales)
         */
        Producto::factory()->create([
            'id' => 8,
            'nombre' => 'Filete de salmón a la plancha',
            'precio' => 12.50,
            'activo' => true,
            'id_categoria' => 3,
            'foto' => 'productos/filete_salmon.webp'
        ]);

        Producto::factory()->create([
            'id' => 9,
            'nombre' => 'Pollo al horno con patatas',
            'precio' => 10.00,
            'activo' => true,
            'id_categoria' => 3,
            'foto' => 'productos/pollo_horno_patatas.webp'
        ]);

        Producto::factory()->create([
            'id' => 10,
            'nombre' => 'Pasta carbonara',
            'precio' => 8.50,
            'activo' => true,
            'id_categoria' => 3,
            'foto' => 'productos/pasta_carbonara.webp'
        ]);

        Producto::factory()->create([
            'id' => 11,
            'nombre' => 'Arroz al senyoret',
            'precio' => 9.50,
            'activo' => true,
            'id_categoria' => 3,
            'foto' => 'productos/arroz_senyoret.webp'
        ]);

        /**
         *  Creación de productos
         *  Categoría 4 (Postres)
         */
        Producto::factory()->create([
            'id' => 12,
            'nombre' => 'Tarta de chocolate',
            'precio' => 4.00,
            'activo' => true,
            'id_categoria' => 4,
            'foto' => 'productos/tarta_chocolate.webp'
        ]);

        Producto::factory()->create([
            'id' => 13, // el numero
            'nombre' => 'Helado de caramelo salado',
            'precio' => 2.60,
            'activo' => true,
            'id_categoria' => 4,
            'foto' => 'productos/helado_caramelosalado.webp'
        ]);

        Producto::factory()->create([
            'id' => 14,
            'nombre' => 'Flan casero',
            'precio' => 3.20,
            'activo' => true,
            'id_categoria' => 4,
            'foto' => 'productos/flan.webp'
        ]);

        /**
         *  Creación de stock de los productos
         */
        for ($i = 1; $i <= self::PRODUCT_QUANTITY; $i++) {
            Stock::factory()->create([
                'cantidad' => rand(10, 75),
                'id_producto' => $i
            ]);
        }

        /**
         *  Creación de mesas
         */
        Mesa::factory()->create([
             'capacidad_maxima' => 7,
             'estado' => 0
        ]);

        Mesa::factory()->create([
             'capacidad_maxima' => 5,
             'estado' => 0
        ]);

        Mesa::factory()->create([
             'capacidad_maxima' => 10,
             'estado' => 0
        ]);

        Mesa::factory()->create([
             'capacidad_maxima' => 2,
             'estado' => 0
        ]);

        Mesa::factory()->create([
             'capacidad_maxima' => 2,
             'estado' => 1
        ]);

        Mesa::factory()->create([
             'capacidad_maxima' => 1,
             'estado' => 1
        ]);

        Mesa::factory()->create([
             'capacidad_maxima' => 6,
             'estado' => 1
        ]);

        Mesa::factory()->create([
             'capacidad_maxima' => 5,
             'estado' => 0
        ]);

        Mesa::factory()->create([
             'capacidad_maxima' => 3,
             'estado' => 0
        ]);

        Mesa::factory()->create([
             'capacidad_maxima' => 12,
             'estado' => 2
        ]);

        Mesa::factory()->create([
             'capacidad_maxima' => 15,
             'estado' => 2
        ]);

        /**
         *  Creación de pedidos
         */
        Pedido::factory()->create([
            'fecha' => now(),
            'estado' => 2,
            'precio' => 19.00,
            'numero_comensales' => 1,
            'id_mesa' => 6,
            'id_usuario' => 3
        ]);

        Pedido::factory()->create([
            'fecha' => now(),
            'estado' => 0,
            'precio' => 19.00,
            'numero_comensales' => 1,
            'id_mesa' => 5,
            'id_usuario' => 4
        ]);

        /**
         *  Creacion de las lineas de los pedidos
         */
        Linea::factory()->create([
            'precio' => 4.00,
            'cantidad' => 1,
            'id_producto' => 12,
            'id_pedido' => 1
        ]);

        Linea::factory()->create([
            'precio' => 10.00,
            'cantidad' => 1,
            'id_producto' => 9,
            'id_pedido' => 1
        ]);

        Linea::factory()->create([
            'precio' => 5.00,
            'cantidad' => 1,
            'id_producto' => 7,
            'id_pedido' => 1
        ]);

        // Pedido 2
        Linea::factory()->create([
            'precio' => 4.00,
            'cantidad' => 1,
            'id_producto' => 12,
            'id_pedido' => 2
        ]);

        Linea::factory()->create([
            'precio' => 10.00,
            'cantidad' => 1,
            'id_producto' => 9,
            'id_pedido' => 2
        ]);

        Linea::factory()->create([
            'precio' => 5.00,
            'cantidad' => 1,
            'id_producto' => 7,
            'id_pedido' => 2
        ]);

        Factura::factory()->create([
            'fecha' => now(),
            'id_pedido' => 1
        ]);

        Factura::factory()->create([
            'fecha' => now(),
            'id_pedido' => 2
        ]);
    }
}
