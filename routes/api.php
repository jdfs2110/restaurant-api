<?php

use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\UserController;
use App\Http\Controllers\api\RoleController;
use App\Http\Controllers\api\CategoriaController;
use App\Http\Controllers\api\ProductoController;
use App\Http\Controllers\api\MesaController;
use App\Http\Controllers\api\PedidoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/registro', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::group(['middleware' => ['auth:sanctum']], function() {
   //protected endpoints
    Route::post('/logout', [AuthController::class, 'logout']);
});

/**
 *  Roles endpoints
 */
Route::get('/roles', [RoleController::class, 'index']);
Route::get('/roles/{id}', [RoleController::class, 'getRole']);
Route::post('/roles/new', [RoleController::class, 'newRole']);

/**
 * User endpoints
 * 1. All users
 * 2. Find user by ID
 * 3. Find all pedidos managed by User
 */
Route::get('/usuarios', [UserController::class, 'index']);
Route::get('/usuarios/{id}', [UserController::class, 'getUser']);
Route::get('usuarios/{id}/pedidos', [UserController::class, 'getUsersPedidos']);

/**
 *  Categorias endpoints
 */
Route::get('/categorias', [CategoriaController::class, 'index']);
Route::get('/categorias/{id}', [CategoriaController::class, 'getCategoria']);
Route::post('/categorias/new', [CategoriaController::class, 'newCategoria']);

/**
 *  Productos endpoints
 */
Route::get('/productos', [ProductoController::class, 'index']);
Route::get('/productos/{id}', [ProductoController::class, 'getProducto']);
Route::post('/productos/new', [ProductoController::class, 'newProduct']);

/**
 * Mesas endpoints
 */
Route::get('/mesas', [MesaController::class, 'index']);
Route::get('/mesas/{id}', [MesaController::class, 'getMesa']);
Route::post('/mesas/new', [MesaController::class, 'newMesa']);

/**
 * Pedidos endpoints
 */
Route::get('/pedidos', [PedidoController::class, 'index']);
Route::get('/pedidos/{id}', [PedidoController::class, 'getPedido']);
Route::post('/pedidos/new', [PedidoController::class, 'newPedido']);
