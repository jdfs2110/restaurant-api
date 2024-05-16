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
        $roles = ['mesero', 'cocina', 'rrhh', 'admin', 'barra', 'bloqueado'];

        foreach ($roles as $role) {
            Role::factory()->create([
                'nombre' => $role
            ]);
        }

        /**
         *  Creación de usuarios
         */
        User::factory()->create([
            'name' => 'jd',
            'email' => 'jdfs@jdfs.dev',
            'password' => bcrypt('123456'),
            'id_rol' => '4',
            'fecha_ingreso' => '2004-10-21'
        ]);

        User::factory()->create([
            'name' => 'Lemuel',
            'email' => 'lemuel@jdfs.dev',
            'password' => bcrypt('123456'),
            'id_rol' => '4',
            'fecha_ingreso' => '1999-11-23'
        ]);

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

        User::factory()->create([
            'name' => 'persona de barra',
            'email' => 'barra@jdfs.dev',
            'password' => bcrypt('123456'),
            'id_rol' => '5',
            'fecha_ingreso' => date('Y-m-d')
        ]);

        User::factory()->create([
            'name' => 'blocked user test',
            'email' => 'blocked@jdfs.dev',
            'password' => bcrypt('123456'),
            'id_rol' => '6',
            'fecha_ingreso' => date('Y-m-d')
        ]);

        User::factory()->count(500)->create();

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
            'precio' => 1.20,
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
                'cantidad' => rand(100, 500),
                'id_producto' => $i
            ]);
        }

        /**
         *  Creación de mesas
         *  ESTADOS:
         *  0: libre
         *  1: ocupada
         *  2: reservada
         */
        Mesa::factory()->create([ // 1
             'capacidad_maxima' => 7,
             'estado' => 0
        ]);

        Mesa::factory()->create([ // 2
             'capacidad_maxima' => 5,
             'estado' => 0
        ]);

        Mesa::factory()->create([ // 3
             'capacidad_maxima' => 10,
             'estado' => 1
        ]);

        Mesa::factory()->create([ // 4
             'capacidad_maxima' => 2,
             'estado' => 1
        ]);

        Mesa::factory()->create([ // 5
             'capacidad_maxima' => 2,
             'estado' => 1
        ]);

        Mesa::factory()->create([ // 6
             'capacidad_maxima' => 1,
             'estado' => 1
        ]);

        Mesa::factory()->create([ // 7
             'capacidad_maxima' => 6,
             'estado' => 2
        ]);

        Mesa::factory()->create([ // 8
             'capacidad_maxima' => 5,
             'estado' => 0
        ]);

        Mesa::factory()->create([ // 9
             'capacidad_maxima' => 3,
             'estado' => 0
        ]);

        Mesa::factory()->create([ // 10
             'capacidad_maxima' => 12,
             'estado' => 2
        ]);

        Mesa::factory()->create([ // 11
             'capacidad_maxima' => 15,
             'estado' => 2
        ]);

        Mesa::factory()->create([ // 12
             'capacidad_maxima' => 15,
             'estado' => 1
        ]);

        /**
         *  Creación de pedidos
         *  ESTADOS:
         *  0: pendiente
         *  1: preparando
         *  2: servido
         *  3: cancelado
         */
        Pedido::factory()->create([ // 1
            'fecha' => now(),
            'estado' => 2,
            'precio' => 19.00,
            'numero_comensales' => 1,
            'id_mesa' => 6,
            'id_usuario' => 5
        ]);

        Pedido::factory()->create([ // 2
            'fecha' => now(),
            'estado' => 0,
            'precio' => 19.00,
            'numero_comensales' => 1,
            'id_mesa' => 5,
            'id_usuario' => 5
        ]);

        Pedido::factory()->create([ // 3
            'fecha' => now(),
            'estado' => 1,
            'precio' => 19.00,
            'numero_comensales' => 1,
            'id_mesa' => 6,
            'id_usuario' => 6
        ]);

        Pedido::factory()->create([ // 4
            'fecha' => now(),
            'estado' => 3,
            'precio' => 19.00,
            'numero_comensales' => 1,
            'id_mesa' => 6,
            'id_usuario' => 6
        ]);

        Pedido::factory()->create([ // 5
            'fecha' => now(),
            'estado' => 0,
            'precio' => 34.40,
            'numero_comensales' => 2,
            'id_mesa' => 4,
            'id_usuario' => 7
        ]);

        Pedido::factory()->create([ // 6
            'fecha' => now(),
            'estado' => 1,
            'precio' => 290.00,
            'numero_comensales' => 14,
            'id_mesa' => 12,
            'id_usuario' => 7
        ]);

        Pedido::factory()->create([ // 7
            'fecha' => now(),
            'estado' => 0,
            'precio' => 215.20,
            'numero_comensales' => 12,
            'id_mesa' => 3,
            'id_usuario' => 6
        ]);

        /**
         *  Creación de las lineas de los pedidos
         */
        Linea::factory()->create([ // Tarta
            'precio' => 4.00,
            'cantidad' => 1,
            'id_producto' => 12,
            'id_pedido' => 1,
            'tipo' => 'cocina',
            'estado' => 0
        ]);

        Linea::factory()->create([ // Pollo al horno
            'precio' => 10.00,
            'cantidad' => 1,
            'id_producto' => 9,
            'id_pedido' => 1,
            'tipo' => 'cocina',
            'estado' => 1
        ]);

        Linea::factory()->create([ // Nachos
            'precio' => 5.00,
            'cantidad' => 1,
            'id_producto' => 7,
            'id_pedido' => 1,
            'tipo' => 'cocina',
            'estado' => 1
        ]);

        // Pedido 2
        Linea::factory()->create([ // Tarta
            'precio' => 4.00,
            'cantidad' => 1,
            'id_producto' => 12,
            'id_pedido' => 2,
            'tipo' => 'cocina',
            'estado' => 0
        ]);

        Linea::factory()->create([ // Pollo al horno
            'precio' => 10.00,
            'cantidad' => 1,
            'id_producto' => 9,
            'id_pedido' => 2,
            'tipo' => 'cocina',
            'estado' => 0
        ]);

        Linea::factory()->create([ // Nachos
            'precio' => 5.00,
            'cantidad' => 1,
            'id_producto' => 7,
            'id_pedido' => 2,
            'tipo' => 'cocina',
            'estado' => 1
        ]);

        // Pedido 3
        Linea::factory()->create([ // Tarta
            'precio' => 4.00,
            'cantidad' => 1,
            'id_producto' => 12,
            'id_pedido' => 3,
            'tipo' => 'cocina',
            'estado' => 1
        ]);

        Linea::factory()->create([ // Pollo al horno
            'precio' => 10.00,
            'cantidad' => 1,
            'id_producto' => 9,
            'id_pedido' => 3,
            'tipo' => 'cocina',
            'estado' => 1
        ]);

        Linea::factory()->create([ // Nachos
            'precio' => 5.00,
            'cantidad' => 1,
            'id_producto' => 7,
            'id_pedido' => 3,
            'tipo' => 'cocina',
            'estado' => 0
        ]);

        // Pedido 4
        Linea::factory()->create([ // Tarta
            'precio' => 4.00,
            'cantidad' => 1,
            'id_producto' => 12,
            'id_pedido' => 4,
            'tipo' => 'cocina',
            'estado' => 0
        ]);

        Linea::factory()->create([ // Pollo al horno
            'precio' => 10.00,
            'cantidad' => 1,
            'id_producto' => 9,
            'id_pedido' => 4,
            'tipo' => 'cocina',
            'estado' => 0
        ]);

        Linea::factory()->create([ // Nachos
            'precio' => 5.00,
            'cantidad' => 1,
            'id_producto' => 7,
            'id_pedido' => 4,
            'tipo' => 'cocina',
            'estado' => 1
        ]);

        // Pedido 5
        Linea::factory()->create([ // Coca-Cola
            'precio' => 1.80,
            'cantidad' => 3,
            'id_producto' => 2,
            'id_pedido' => 5,
            'tipo' => 'barra',
            'estado' => 0
        ]);

        Linea::factory()->create([ // Filete de salmón
            'precio' => 12.50,
            'cantidad' => 1,
            'id_producto' => 8,
            'id_pedido' => 5,
            'tipo' => 'cocina',
            'estado' => 0
        ]);

        Linea::factory()->create([ // Pasta carbonara
            'precio' => 8.50,
            'cantidad' => 1,
            'id_producto' => 10,
            'id_pedido' => 5,
            'tipo' => 'cocina',
            'estado' => 0
        ]);

        Linea::factory()->create([ // Tarta
            'precio' => 4,
            'cantidad' => 2,
            'id_producto' => 12,
            'id_pedido' => 5,
            'tipo' => 'cocina',
            'estado' => 0
        ]);

        // Pedido 6
        Linea::factory()->create([ // Coca-Cola
            'precio' => 1.80,
            'cantidad' => 6,
            'id_producto' => 2,
            'id_pedido' => 6,
            'tipo' => 'barra',
            'estado' => 0
        ]);

        Linea::factory()->create([ // Cerveza
            'precio' => 1.20,
            'cantidad' => 8,
            'id_producto' => 3,
            'id_pedido' => 6,
            'tipo' => 'barra',
            'estado' => 1
        ]);

        Linea::factory()->create([ // Agua
            'precio' => 1.20,
            'cantidad' => 2,
            'id_producto' => 1,
            'id_pedido' => 6,
            'tipo' => 'barra',
            'estado' => 1
        ]);

        Linea::factory()->create([ // Sopa
            'precio' => 3.60,
            'cantidad' => 6,
            'id_producto' => 6,
            'id_pedido' => 6,
            'tipo' => 'cocina',
            'estado' => 0
        ]);

        Linea::factory()->create([ // Nachos
            'precio' => 5.00,
            'cantidad' => 8,
            'id_producto' => 7,
            'id_pedido' => 6,
            'tipo' => 'cocina',
            'estado' => 1
        ]);

        Linea::factory()->create([ // Pollo al horno
            'precio' => 10.00,
            'cantidad' => 2,
            'id_producto' => 9,
            'id_pedido' => 6,
            'tipo' => 'cocina',
            'estado' => 0
        ]);

        Linea::factory()->create([ // Filete de salmón
            'precio' => 12.50,
            'cantidad' => 8,
            'id_producto' => 8,
            'id_pedido' => 6,
            'tipo' => 'cocina',
            'estado' => 0
        ]);

        Linea::factory()->create([ // Pasta carbonara
            'precio' => 8.50,
            'cantidad' => 2,
            'id_producto' => 10,
            'id_pedido' => 6,
            'tipo' => 'cocina',
            'estado' => 0
        ]);

        Linea::factory()->create([ // Arroz
            'precio' => 9.50,
            'cantidad' => 2,
            'id_producto' => 11,
            'id_pedido' => 6,
            'tipo' => 'cocina',
            'estado' => 0
        ]);

        Linea::factory()->create([ // Tarta
            'precio' => 4.00,
            'cantidad' => 9,
            'id_producto' => 12,
            'id_pedido' => 6,
            'tipo' => 'cocina',
            'estado' => 1
        ]);

        Linea::factory()->create([ // Helado
            'precio' => 2.60,
            'cantidad' => 4,
            'id_producto' => 13,
            'id_pedido' => 6,
            'tipo' => 'cocina',
            'estado' => 1
        ]);

        Linea::factory()->create([ // Flan
            'precio' => 3.20,
            'cantidad' => 1,
            'id_producto' => 14,
            'id_pedido' => 6,
            'tipo' => 'cocina',
            'estado' => 0
        ]);

        // Pedido 7 (12 personas)
        Linea::factory()->create([ // Agua
            'precio' => 1.20,
            'cantidad' => 7,
            'id_producto' => 1,
            'id_pedido' => 7,
            'tipo' => 'barra',
            'estado' => 1
        ]);

        Linea::factory()->create([ // Fanta naranja
            'precio' => 1.80,
            'cantidad' => 2,
            'id_producto' => 4,
            'id_pedido' => 7,
            'tipo' => 'barra',
            'estado' => 1
        ]);

        Linea::factory()->create([ // Cerveza
            'precio' => 1.20,
            'cantidad' => 3,
            'id_producto' => 3,
            'id_pedido' => 7,
            'tipo' => 'barra',
            'estado' => 0
        ]);

        Linea::factory()->create([ // Coca-Cola
            'precio' => 1.80,
            'cantidad' => 2,
            'id_producto' => 2,
            'id_pedido' => 7,
            'tipo' => 'barra',
            'estado' => 1
        ]);

        Linea::factory()->create([ // Ensalada césar
            'precio' => 4.50,
            'cantidad' => 4,
            'id_producto' => 5,
            'id_pedido' => 7,
            'tipo' => 'cocina',
            'estado' => 1
        ]);

        Linea::factory()->create([ // Filete de salmón
            'precio' => 12.50,
            'cantidad' => 10,
            'id_producto' => 8,
            'id_pedido' => 7,
            'tipo' => 'cocina',
            'estado' => 0
        ]);

        Linea::factory()->create([ // Pasta carbonara
            'precio' => 8.50,
            'cantidad' => 2,
            'id_producto' => 10,
            'id_pedido' => 7,
            'tipo' => 'cocina',
            'estado' => 0
        ]);

        Linea::factory()->create([ // Tarta
            'precio' => 4.00,
            'cantidad' => 9,
            'id_producto' => 12,
            'id_pedido' => 7,
            'tipo' => 'cocina',
            'estado' => 0
        ]);

        /**
         *  Creación de las facturas
         */
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
