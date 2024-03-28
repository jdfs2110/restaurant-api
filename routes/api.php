<?php

use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\UserController;
use App\Http\Controllers\api\RoleController;
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
 * User endpoints
 */
Route::get('/usuarios', [UserController::class, 'index']);
Route::get('/usuarios/{id}', [UserController::class, 'getUser']);


/**
 *  Roles endpoints
 */
Route::get('/roles', [RoleController::class, 'index']);
Route::get('/roles/{id}', [RoleController::class, 'getRole']);
Route::post('/roles/new', [RoleController::class, 'newRole']);

/**
 *  Productos endpoints
 */
Route::get('/productos', [ProductoController::class, 'index']);
Route::get('/productos/{id}', [ProductoController::class, 'getProducto']);

/**
 * Mesas endpoints
 */
Route::get('/mesas', [MesaController::class, 'index']);
Route::get('/mesas/{id}', [MesaController::class, 'getMesa']);

/**
 * Pedidos endpoints
 */
Route::get('/pedidos', [PedidoController::class, 'index']);
Route::get('/pedidos/{id}', [PedidoController::class, 'getPedido']);
