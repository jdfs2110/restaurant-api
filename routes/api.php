<?php

use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\UserController;
use App\Http\Controllers\api\RoleController;
use App\Http\Controllers\api\CategoriaController;
use App\Http\Controllers\api\ProductoController;
use App\Http\Controllers\api\StockController;
use App\Http\Controllers\api\MesaController;
use App\Http\Controllers\api\PedidoController;
use Illuminate\Support\Facades\Route;

Route::post('/registro', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::group(['middleware' => ['auth:sanctum']], function() {
   //protected endpoints
    Route::post('/logout', [AuthController::class, 'logout']);
});

/**
 *  Roles endpoints
 *  1. Todos los roles
 *  2. Buscar un rol por ID
 *  3. Crear un rol
 *  4. Eliminar un rol
 *  5. Editar un rol
 */
Route::get('/roles', [RoleController::class, 'index']);
Route::get('/roles/{id}', [RoleController::class, 'getRole']);
Route::post('/roles/new', [RoleController::class, 'newRole']);
Route::delete('/roles/{id}', [RoleController::class, 'deleteRole']);
Route::put('/roles/{id}', [RoleController::class, 'updateRole']);

/**
 * User endpoints
 * 1. Todos los usuarios
 * 2. Buscar un usuario por ID
 * 3. Buscar todos los pedidos manejados por un usuario concreto (ID)
 */
Route::get('/usuarios', [UserController::class, 'index']);
Route::get('/usuarios/{id}', [UserController::class, 'getUser']);
Route::get('usuarios/{id}/pedidos', [UserController::class, 'getUsersPedidos']);

/**
 *  Categorias endpoints
 *  1. Todas las categorias
 *  2. Buscar una categoría por ID
 *  3. Crear una categoría
 *  4. Eliminar una categoría
 *  5. Todos los productos de una categoría (ID)
 *  6. Editar una categoría (ID)
 */
Route::get('/categorias', [CategoriaController::class, 'index']);
Route::get('/categorias/{id}', [CategoriaController::class, 'getCategoria']);
Route::post('/categorias/new', [CategoriaController::class, 'newCategoria']);
Route::delete('/categorias/{id}', [CategoriaController::class, 'deleteCategoria']);
Route::get('/categorias/{id}/productos', [ProductoController::class, 'getProductosByCategoria']);
Route::put('/categorias/{id}', [CategoriaController::class, 'updateCategoria']);

/**
 *  Productos endpoints
 *  1. Todos los productos
 *  2. Buscar un producto por ID
 *  3. Crear un producto
 *  4. Eliminar un producto
 *  5. Editar un producto
 */
Route::get('/productos', [ProductoController::class, 'index']);
Route::get('/productos/{id}', [ProductoController::class, 'getProducto']);
Route::post('/productos/new', [ProductoController::class, 'newProducto']);
Route::delete('/productos/{id}', [ProductoController::class, 'deleteProducto']);
Route::put('/productos/{id}', [ProductoController::class, 'updateProducto']);

/**
 *  Stock endpoints
 *  1. El stock de todos los productos
 *  2. Buscar un stock por id (probablemente no se va a utilizar)
 *  3. Buscar el stock de un producto (ID producto)
 *  4. Dar de alta un producto en stock
 *  5. Editar un stock
 */
Route::get('/stock', [StockController::class, 'index']);
Route::get('/stock/{id}', [StockController::class, 'getStock']);
Route::get('/productos/{id}/stock', [StockController::class, 'getProductStock']);
Route::post('/stock/new', [StockController::class, 'createStock']);
Route::put('/stock/{id}', [StockController::class, 'updateStock']);

/**
 *  Mesas endpoints
 *  1. Todas las mesas
 *  2. Buscar una mesa por ID
 *  3. Crear una mesa
 *  4. Eliminar una mesa
 */
Route::get('/mesas', [MesaController::class, 'index']);
Route::get('/mesas/{id}', [MesaController::class, 'getMesa']);
Route::post('/mesas/new', [MesaController::class, 'newMesa']);
Route::delete('/mesas/{id}', [MesaController::class, 'deleteMesa']);

/**
 *  Pedidos endpoints
 *  1. Todos los pedidos
 *  2. Buscar un pedido por ID
 *  3. Crear un pedido
 */
Route::get('/pedidos', [PedidoController::class, 'index']);
Route::get('/pedidos/{id}', [PedidoController::class, 'getPedido']);
Route::post('/pedidos/new', [PedidoController::class, 'newPedido']);
//Route::get('/pedidos/{id}/lineas');
