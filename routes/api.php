<?php

use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\UserController;
use App\Http\Controllers\api\RoleController;
use App\Http\Controllers\api\CategoriaController;
use App\Http\Controllers\api\ProductoController;
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
 */
Route::get('/roles', [RoleController::class, 'index']);
Route::get('/roles/{id}', [RoleController::class, 'getRole']);
Route::post('/roles/new', [RoleController::class, 'newRole']);
Route::delete('/roles/{id}', [RoleController::class, 'deleteRole']);

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
 */
Route::get('/categorias', [CategoriaController::class, 'index']);
Route::get('/categorias/{id}', [CategoriaController::class, 'getCategoria']);
Route::post('/categorias/new', [CategoriaController::class, 'newCategoria']);
Route::delete('/categorias/{id}', [CategoriaController::class, 'deleteCategoria']);

/**
 *  Productos endpoints
 *  1. Todos los productos
 *  2. Buscar un producto por ID
 *  3. Crear un producto
 */
Route::get('/productos', [ProductoController::class, 'index']);
Route::get('/productos/{id}', [ProductoController::class, 'getProducto']);
Route::post('/productos/new', [ProductoController::class, 'newProduct']);

/**
 *  Mesas endpoints
 *  1. Todas las mesas
 *  2. Buscar una mesa por ID
 *  3. Crear una mesa
 */
Route::get('/mesas', [MesaController::class, 'index']);
Route::get('/mesas/{id}', [MesaController::class, 'getMesa']);
Route::post('/mesas/new', [MesaController::class, 'newMesa']);

/**
 *  Pedidos endpoints
 *  1. Todos los pedidos
 *  2. Buscar un pedido por ID
 *  3. Crear un pedido
 */
Route::get('/pedidos', [PedidoController::class, 'index']);
Route::get('/pedidos/{id}', [PedidoController::class, 'getPedido']);
Route::post('/pedidos/new', [PedidoController::class, 'newPedido']);
