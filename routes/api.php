<?php

use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\UserController;
use App\Http\Controllers\api\RoleController;
use App\Http\Controllers\api\CategoriaController;
use App\Http\Controllers\api\ProductoController;
use App\Http\Controllers\api\StockController;
use App\Http\Controllers\api\MesaController;
use App\Http\Controllers\api\PedidoController;
use App\Http\Controllers\api\LineaController;
use App\Http\Controllers\api\FacturaController;
use App\Http\Middleware\AdminCheck;
use App\Http\Middleware\BarraAndCocinaCheck;
use App\Http\Middleware\BarraAndMeseroCheck;
use App\Http\Middleware\BarraCheck;
use App\Http\Middleware\CocineroCheck;
use App\Http\Middleware\MeseroAndCocineroCheck;
use App\Http\Middleware\MeseroCheck;
use App\Http\Middleware\UserIsOwnerCheck;
use App\Http\Middleware\UserNotBlockedCheck;
use Illuminate\Support\Facades\Route;


Route::post('/registro', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::group(['middleware' => ['auth:sanctum', UserNotBlockedCheck::class]], function () {
    //protected endpoints
    Route::post('/logout', [AuthController::class, 'logout'])->withoutMiddleware(UserNotBlockedCheck::class);

    /**
     *  Roles endpoints
     *  1. Todos los roles
     *  3. Buscar un rol por ID
     *  4. Crear un rol
     *  5. Eliminar un rol
     *  6. Editar un rol
     *  7. Listar usuarios por rol concreto (ID)
     */
    Route::prefix('/roles')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->middleware([AdminCheck::class]);
        Route::get('/{id}', [RoleController::class, 'getRole'])->middleware([AdminCheck::class]);
        Route::post('/new', [RoleController::class, 'newRole'])->middleware([AdminCheck::class]);
        Route::delete('/{id}', [RoleController::class, 'deleteRole'])->middleware([AdminCheck::class]);
        Route::put('/{id}', [RoleController::class, 'updateRole'])->middleware([AdminCheck::class]);
        Route::get('/{id}/usuarios', [UserController::class, 'getAllUsersByRole'])->middleware([AdminCheck::class]);
    });

    /**
     *  User endpoints
     *  1. Usuarios paginados
     *  2. Cantidad de páginas que tienen los usuarios
     *  3. Buscar un usuario por ID
     *  4. Buscar todos los pedidos manejados por un usuario concreto (ID)
     *  5. Editar un usuario
     *  6. Eliminar un usuario
     */
    Route::prefix('/usuarios')->group(function () {
        Route::get('/', [UserController::class, 'index'])->middleware([AdminCheck::class]);
        Route::get('/pages', [UserController::class, 'getAmountOfpages'])->middleware([AdminCheck::class]);
        Route::get('/{id}', [UserController::class, 'getUser'])->middleware([UserIsOwnerCheck::class]);
        Route::get('/{id}/pedidos', [UserController::class, 'getUsersPedidos']); // TODO: mirar los permisos para esta ruta
        Route::put('/{id}', [UserController::class, 'updateUser'])->middleware([UserIsOwnerCheck::class]);
        Route::delete('/{id}', [UserController::class, 'deleteUser'])->middleware([AdminCheck::class]);
    });

    /**
     *  Categorias endpoints
     *  1. Categorias paginadas
     *  2. Cantidad de páginas que tienen las categorias
     *  3. Buscar una categoría por ID
     *  4. Crear una categoría
     *  5. Eliminar una categoría
     *  6. Todos los productos de una categoría (ID)
     *  7. Editar una categoría (ID)
     */
    Route::prefix('/categorias')->group(function () {
        Route::get('/', [CategoriaController::class, 'index'])->middleware([AdminCheck::class]);
        Route::get('/pages', [CategoriaController::class, 'getAmountOfPages'])->middleware([AdminCheck::class]);
        Route::get('/{id}', [CategoriaController::class, 'getCategoria'])->middleware([AdminCheck::class]);
        Route::post('/new', [CategoriaController::class, 'newCategoria'])->middleware([AdminCheck::class]);
        Route::delete('/{id}', [CategoriaController::class, 'deleteCategoria'])->middleware([AdminCheck::class]);
        Route::get('/{id}/productos', [ProductoController::class, 'getProductosByCategoria']);
        Route::put('/{id}', [CategoriaController::class, 'updateCategoria'])->middleware([AdminCheck::class]);
    });

    /**
     *  Productos endpoints
     *  1. Productos paginados
     *  2. Cantidad de páginas que tienen los productos
     *  3. Buscar un producto por ID
     *  4. Crear un producto
     *  5. Eliminar un producto
     *  6. Editar un producto
     *  7. Buscar el stock de un producto (ID producto)
     *  8. Aumentar el stock de un producto
     *  9. Reducir el stock de un producto
     */
    Route::prefix('/productos')->group(function () {
        Route::get('/', [ProductoController::class, 'index']);
        Route::get('/pages', [ProductoController::class, 'getAmountOfPages']);
        Route::get('/{id}', [ProductoController::class, 'getProducto']);
        Route::post('/new', [ProductoController::class, 'newProducto'])->middleware([AdminCheck::class]);
        Route::delete('/{id}', [ProductoController::class, 'deleteProducto'])->middleware([AdminCheck::class]);
        Route::put('/{id}', [ProductoController::class, 'updateProducto']); // TODO: check perms
        Route::get('/{id}/stock', [ProductoController::class, 'getProductStock']);
        Route::post('/{id}/stock/add', [ProductoController::class, 'addStock']);
        Route::post('/{id}/stock/reduce', [ProductoController::class, 'reduceStock']);
    });

    /**
     *  Stock endpoints
     *  1. El stock de todos los productos paginado
     *  2. Cantidad de páginas existentes
     *  3. Dar de alta un producto en stock (Dudo que se vaya a utilizar)
     *  4. Editar un stock
     */
    Route::prefix('/stock')->group(function () {
        Route::get('/', [StockController::class, 'index']);
        Route::get('/pages', [StockController::class, 'getAmountOfPages']);
        Route::post('/new', [StockController::class, 'createStock'])->middleware([AdminCheck::class]);
        Route::put('/{id}', [StockController::class, 'updateStock'])->middleware([CocineroCheck::class, MeseroCheck::class]);
    });

    /**
     *  Mesas endpoints
     *  1. Todas las mesas
     *  2. Buscar una mesa por ID
     *  3. Crear una mesa
     *  4. Eliminar una mesa
     *  5. Editar una mesa
     *  6. Listar todos los pedidos de una mesa
     *  7. Listar el pedido actual de una mesa
     */
    Route::prefix('/mesas')->group(function () {
        Route::get('/', [MesaController::class, 'index'])->middleware([MeseroAndCocineroCheck::class]);
        Route::get('/{id}', [MesaController::class, 'getMesa'])->middleware([MeseroAndCocineroCheck::class]);
        Route::post('/new', [MesaController::class, 'newMesa'])->middleware([AdminCheck::class]);
        Route::delete('/{id}', [MesaController::class, 'deleteMesa'])->middleware([AdminCheck::class]);
        Route::put('/{id}', [MesaController::class, 'updateMesa'])->middleware([MeseroCheck::class]);
        Route::get('/{id}/pedidos', [MesaController::class, 'getPedidosByMesa'])->middleware([MeseroAndCocineroCheck::class]);
        Route::get('/{id}/pedido', [MesaController::class, 'getPedidoActual'])->middleware([MeseroAndCocineroCheck::class]);
    });

    /**
     *  Pedidos endpoints
     *  1. Pedidos paginados
     *  2. Cantidad de páginas que tienen los pedidos
     *  3. Buscar un pedido por ID
     *  4. Crear un pedido
     *  5. Editar un pedido
     *  6. Eliminar un pedido
     *  7. Buscar las líneas de un pedido
     *  8. Buscar la factura de un pedido
     *  9. Cambiar el estado de un pedido a 'servido'
     */
    Route::prefix('/pedidos')->group(function () {
        Route::get('/', [PedidoController::class, 'index'])->middleware([MeseroAndCocineroCheck::class]);
        Route::get('/pages', [PedidoController::class, 'getAmountOfPages'])->middleware([MeseroAndCocineroCheck::class]);
        Route::get('/{id}', [PedidoController::class, 'getPedido'])->middleware([MeseroAndCocineroCheck::class]);
        Route::post('/new', [PedidoController::class, 'newPedido'])->middleware([MeseroCheck::class]);
        Route::put('/{id}', [PedidoController::class, 'updatePedido'])->middleware([MeseroAndCocineroCheck::class]);
        Route::delete('/{id}', [PedidoController::class, 'deletePedido'])->middleware([MeseroCheck::class]);
        Route::get('/{id}/lineas', [LineaController::class, 'getLineasByPedido'])->middleware([MeseroAndCocineroCheck::class]);
        Route::get('/{id}/factura', [FacturaController::class, 'getFacturaByPedido']); // TODO: mirar los permisos para esta ruta
        Route::post('/{id}/servir', [PedidoController::class, 'servirPedido'])->middleware([MeseroCheck::class]);
    });

    /**
     *  Líneas endpoints
     *  1. Líneas paginadas
     *  2. Cantidad de páginas que tienen las líneas
     *  3. Buscar una línea por ID
     *  4. Crear una línea
     *  5. Modificar una línea
     *  6. Eliminar una línea
     *  7. Recuperar las líneas de cocina (solo pendientes)
     *  8. Recuperar las líneas de la barra (solo pendientes)
     *  9. Completar una línea
     */
    Route::prefix('/lineas')->group(function () {
        Route::get('/', [LineaController::class, 'index'])->middleware([AdminCheck::class]);
        Route::get('/pages', [LineaController::class, 'getAmountOfPages'])->middleware([AdminCheck::class]);
        Route::get('/{id}', [LineaController::class, 'getLinea'])->middleware([MeseroCheck::class]);
        Route::post('/new', [LineaController::class, 'newLinea'])->middleware([MeseroCheck::class]);
        Route::put('/{id}', [LineaController::class, 'updateLinea'])->middleware([MeseroCheck::class]);
        Route::delete('/{id}', [LineaController::class, 'deleteLinea'])->middleware([MeseroCheck::class]);
        Route::get('/tipo/cocina', [LineaController::class, 'getLineasOfCocina'])->middleware([MeseroAndCocineroCheck::class]);
        Route::get('/tipo/barra', [LineaController::class, 'getLineasOfBarra'])->middleware([BarraAndMeseroCheck::class]);
        Route::post('/{id}/completar', [LineaController::class, 'completarLinea'])->middleware([BarraAndCocinaCheck::class]);
    });

    /**
     *  Facturas endpoints
     *  1. Facturas paginadas
     *  2. Cantidad de páginas que tienen las facturas
     *  2. Buscar una factura por ID
     *  3. Crear una factura
     *  4. Modificar una factura
     *  5. Eliminar una factura
     */
    Route::prefix('/facturas')->group(function () {
        Route::get('/', [FacturaController::class, 'index'])->middleware([AdminCheck::class]); // TODO: admin onlu??
        Route::get('/pages', [FacturaController::class, 'getAmountOfPages'])->middleware([AdminCheck::class]); // TODO: admin only??
        Route::get('/{id}', [FacturaController::class, 'getFactura'])->middleware([AdminCheck::class]); // TODO: admin only??
        Route::post('/new', [FacturaController::class, 'newFactura'])->middleware([MeseroCheck::class]);
        Route::put('/{id}', [FacturaController::class, 'updateFactura'])->middleware([MeseroCheck::class]);
        Route::delete('/{id}', [FacturaController::class, 'deleteFactura'])->middleware([AdminCheck::class]);
    });
});
