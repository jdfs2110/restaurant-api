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
 *  6. Listar usuarios por rol concreto (ID)
 */
Route::get('/roles', [RoleController::class, 'index']);
Route::get('/roles/{id}', [RoleController::class, 'getRole']);
Route::post('/roles/new', [RoleController::class, 'newRole']);
Route::delete('/roles/{id}', [RoleController::class, 'deleteRole']);
Route::put('/roles/{id}', [RoleController::class, 'updateRole']);
Route::get('/roles/{id}/usuarios', [UserController::class, 'getAllUsersByRole']);

/**
 * User endpoints
 * 1. Todos los usuarios
 * 2. Buscar un usuario por ID
 * 3. Buscar todos los pedidos manejados por un usuario concreto (ID)
 * 4. Editar un usuario
 */
Route::get('/usuarios', [UserController::class, 'index']);
Route::get('/usuarios/{id}', [UserController::class, 'getUser']);
Route::get('/usuarios/{id}/pedidos', [UserController::class, 'getUsersPedidos']);
Route::put('/usuarios/{id}', [UserController::class, 'updateUser']);

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
 *  2. Buscar un stock por ID (probablemente no se va a utilizar)
 *  3. Buscar el stock de un producto (ID producto)
 *  4. Dar de alta un producto en stock
 *  5. Editar un stock
 *  6. Eliminar un stock
 */
Route::get('/stock', [StockController::class, 'index']);
Route::get('/stock/{id}', [StockController::class, 'getStock']);
Route::get('/productos/{id}/stock', [StockController::class, 'getProductStock']);
Route::post('/stock/new', [StockController::class, 'createStock']);
Route::put('/stock/{id}', [StockController::class, 'updateStock']);
Route::delete('/stock/{id}', [StockController::class, 'deleteStock']);

/**
 *  Mesas endpoints
 *  1. Todas las mesas
 *  2. Buscar una mesa por ID
 *  3. Crear una mesa
 *  4. Eliminar una mesa
 *  5. Editar una mesa
 */
Route::get('/mesas', [MesaController::class, 'index']);
Route::get('/mesas/{id}', [MesaController::class, 'getMesa']);
Route::post('/mesas/new', [MesaController::class, 'newMesa']);
Route::delete('/mesas/{id}', [MesaController::class, 'deleteMesa']);
Route::put('/mesas/{id}', [MesaController::class, 'updateMesa']);

/**
 *  Pedidos endpoints
 *  1. Todos los pedidos
 *  2. Buscar un pedido por ID
 *  3. Crear un pedido
 *  4. Editar un pedido
 *  5. Eliminar un pedido
 *  6. Buscar las líneas de un pedido
 *  7. Buscar la factura de un pedido
 */
Route::get('/pedidos', [PedidoController::class, 'index']);
Route::get('/pedidos/{id}', [PedidoController::class, 'getPedido']);
Route::post('/pedidos/new', [PedidoController::class, 'newPedido']);
Route::put('/pedidos/{id}', [PedidoController::class, 'updatePedido']);
Route::delete('/pedidos/{id}', [PedidoController::class, 'deletePedido']);
Route::get('/pedidos/{id}/lineas', [LineaController::class, 'getLineasByPedido']);
Route::get('/pedidos/{id}/factura', [FacturaController::class, 'getFacturaByPedido']);

/**
 *  Líneas endpoints
 *  1. Todas las líneas (pretty pointless)
 *  2. Buscar una línea por ID
 *  3. Crear una línea
 *  4. Modificar una línea
 *  5. Eliminar una línea
 */
Route::get('/lineas', [LineaController::class, 'index']);
Route::get('/lineas/{id}', [LineaController::class, 'getLinea']);
Route::post('/lineas/new', [LineaController::class, 'newLinea']);
Route::put('/lineas/{id}', [LineaController::class, 'updateLinea']);
Route::delete('/lineas/{id}', [LineaController::class, 'deleteLinea']);

/**
 *  Facturas endpoints
 *  1. Todas las facturas
 *  2. Buscar una factura por ID
 *  3. Crear una factura
 *  4. Modificar una factura
 *  5. Eliminar una factura
 */
Route::get('/facturas', [FacturaController::class, 'index']);
Route::get('/facturas/{id}', [FacturaController::class, 'getFactura']);
Route::post('/facturas/new', [FacturaController::class, 'newFactura']);
Route::put('/facturas/{id}', [FacturaController::class, 'updateFactura']);
Route::delete('/facturas/{id}', [FacturaController::class, 'deleteFactura']);
