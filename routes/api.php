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
use App\Http\Middleware\CocineroCheck;
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
    Route::get('/roles', [RoleController::class, 'index'])->middleware([AdminCheck::class]);
    Route::get('/roles/{id}', [RoleController::class, 'getRole'])->middleware([AdminCheck::class]);
    Route::post('/roles/new', [RoleController::class, 'newRole'])->middleware([AdminCheck::class]);
    Route::delete('/roles/{id}', [RoleController::class, 'deleteRole'])->middleware([AdminCheck::class]);
    Route::put('/roles/{id}', [RoleController::class, 'updateRole'])->middleware([AdminCheck::class]);
    Route::get('/roles/{id}/usuarios', [UserController::class, 'getAllUsersByRole'])->middleware([AdminCheck::class]);

    /**
     *  User endpoints
     *  1. Usuarios paginados
     *  2. Cantidad de páginas que tienen los usuarios
     *  3. Buscar un usuario por ID
     *  4. Buscar todos los pedidos manejados por un usuario concreto (ID)
     *  5. Editar un usuario
     *  6. Eliminar un usuario
     */
    Route::get('/usuarios', [UserController::class, 'index'])->middleware([AdminCheck::class]);
    Route::get('/usuarios/pages', [UserController::class, 'getAmountOfpages'])->middleware([AdminCheck::class]);
    Route::get('/usuarios/{id}', [UserController::class, 'getUser']); // TODO: mirar los permisos para esta ruta
    Route::get('/usuarios/{id}/pedidos', [UserController::class, 'getUsersPedidos']); // TODO: mirar los permisos para esta ruta
    Route::put('/usuarios/{id}', [UserController::class, 'updateUser'])->middleware([UserIsOwnerCheck::class]);
    Route::delete('/usuarios/{id}', [UserController::class, 'deleteUser'])->middleware([AdminCheck::class]);

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
    Route::get('/categorias', [CategoriaController::class, 'index'])->middleware([AdminCheck::class]);
    Route::get('/categorias/pages', [CategoriaController::class, 'getAmountOfPages'])->middleware([AdminCheck::class]);
    Route::get('/categorias/{id}', [CategoriaController::class, 'getCategoria']); // TODO: mirar los permisos para esta ruta
    Route::post('/categorias/new', [CategoriaController::class, 'newCategoria'])->middleware([AdminCheck::class]);
    Route::delete('/categorias/{id}', [CategoriaController::class, 'deleteCategoria'])->middleware([AdminCheck::class]);
    Route::get('/categorias/{id}/productos', [ProductoController::class, 'getProductosByCategoria']); // TODO: mirar los permisos para esta ruta
    Route::put('/categorias/{id}', [CategoriaController::class, 'updateCategoria']); // TODO: mirar los permisos para esta ruta

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
    Route::get('/productos', [ProductoController::class, 'index']); // TODO: mirar los permisos para esta ruta
    Route::get('/productos/pages', [ProductoController::class, 'getAmountOfPages']); // TODO: mirar los permisos para esta ruta
    Route::get('/productos/{id}', [ProductoController::class, 'getProducto']); // TODO: mirar los permisos para esta ruta
    Route::post('/productos/new', [ProductoController::class, 'newProducto']); // TODO: mirar los permisos para esta ruta
    Route::delete('/productos/{id}', [ProductoController::class, 'deleteProducto'])->middleware([AdminCheck::class]);
    Route::put('/productos/{id}', [ProductoController::class, 'updateProducto']); // TODO: mirar los permisos para esta ruta
    Route::get('/productos/{id}/stock', [ProductoController::class, 'getProductStock']); // TODO: mirar los permisos para esta ruta
    Route::post('/productos/{id}/stock/add', [ProductoController::class, 'addStock']); // TODO: mirar los permisos para esta ruta
    Route::post('/productos/{id}/stock/reduce', [ProductoController::class, 'reduceStock']); // TODO: mirar los permisos para esta ruta

    /**
     *  Stock endpoints
     *  1. El stock de todos los productos paginado
     *  2. Cantidad de páginas existentes
     *  2. Dar de alta un producto en stock (Dudo que se vaya a utilizar)
     *  3. Editar un stock
     */
    Route::get('/stock', [StockController::class, 'index']); // TODO: mirar los permisos para esta ruta
    Route::get('/stock/pages', [StockController::class, 'getAmountOfPages']); // TODO: mirar los permisos para esta ruta
    Route::post('/stock/new', [StockController::class, 'createStock']); // TODO: mirar los permisos para esta ruta
    Route::put('/stock/{id}', [StockController::class, 'updateStock']); // TODO: mirar los permisos para esta ruta

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
    Route::get('/mesas', [MesaController::class, 'index']); // TODO: mirar los permisos para esta ruta
    Route::get('/mesas/{id}', [MesaController::class, 'getMesa']); // TODO: mirar los permisos para esta ruta
    Route::post('/mesas/new', [MesaController::class, 'newMesa'])->middleware([AdminCheck::class]);
    Route::delete('/mesas/{id}', [MesaController::class, 'deleteMesa'])->middleware([AdminCheck::class]);
    Route::put('/mesas/{id}', [MesaController::class, 'updateMesa']); // TODO: mirar los permisos para esta ruta
    Route::get('mesas/{id}/pedidos', [MesaController::class, 'getPedidosByMesa']); // TODO: mirar los permisos para esta ruta
    Route::get('/mesas/{id}/pedido', [MesaController::class, 'getPedidoActual']); // TODO: mirar los permisos para esta ruta

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
    Route::get('/pedidos', [PedidoController::class, 'index'])->middleware([MeseroCheck::class]);
    Route::get('/pedidos/pages', [PedidoController::class, 'getAmountOfPages'])->middleware([MeseroCheck::class]);
    Route::get('/pedidos/{id}', [PedidoController::class, 'getPedido']); // TODO: mirar los permisos para esta ruta
    Route::post('/pedidos/new', [PedidoController::class, 'newPedido'])->middleware([MeseroCheck::class]);
    Route::put('/pedidos/{id}', [PedidoController::class, 'updatePedido'])->middleware([MeseroCheck::class, CocineroCheck::class]);
    Route::delete('/pedidos/{id}', [PedidoController::class, 'deletePedido'])->middleware([MeseroCheck::class, CocineroCheck::class]);
    Route::get('/pedidos/{id}/lineas', [LineaController::class, 'getLineasByPedido']); // TODO: mirar los permisos para esta ruta
    Route::get('/pedidos/{id}/factura', [FacturaController::class, 'getFacturaByPedido']); // TODO: mirar los permisos para esta ruta
    Route::post('/pedidos/{id}/servir', [PedidoController::class, 'servirPedido']); // TODO: mirar los permisos para esta ruta

    /**
     *  Líneas endpoints
     *  1. Líneas paginadas
     *  2. Cantidad de páginas que tienen las líneas
     *  3. Buscar una línea por ID
     *  4. Crear una línea
     *  5. Modificar una línea
     *  6. Eliminar una línea
     */
    Route::get('/lineas', [LineaController::class, 'index']); // TODO: mirar los permisos para esta ruta
    Route::get('/lineas/pages', [LineaController::class, 'getAmountOfPages']); // TODO: mirar los permisos para esta ruta
    Route::get('/lineas/{id}', [LineaController::class, 'getLinea']); // TODO: mirar los permisos para esta ruta
    Route::post('/lineas/new', [LineaController::class, 'newLinea']); // TODO: mirar los permisos para esta ruta
    Route::put('/lineas/{id}', [LineaController::class, 'updateLinea']); // TODO: mirar los permisos para esta ruta
    Route::delete('/lineas/{id}', [LineaController::class, 'deleteLinea']); // TODO: mirar los permisos para esta ruta

    /**
     *  Facturas endpoints
     *  1. Facturas paginadas
     *  2. Cantidad de páginas que tienen las facturas
     *  2. Buscar una factura por ID
     *  3. Crear una factura
     *  4. Modificar una factura
     *  5. Eliminar una factura
     */
    Route::get('/facturas', [FacturaController::class, 'index']); // TODO: mirar los permisos para esta ruta
    Route::get('/facturas/pages', [FacturaController::class, 'getAmountOfPages']); // TODO: mirar los permisos para esta ruta
    Route::get('/facturas/{id}', [FacturaController::class, 'getFactura']); // TODO: mirar los permisos para esta ruta
    Route::post('/facturas/new', [FacturaController::class, 'newFactura']); // TODO: mirar los permisos para esta ruta
    Route::put('/facturas/{id}', [FacturaController::class, 'updateFactura']); // TODO: mirar los permisos para esta ruta
    Route::delete('/facturas/{id}', [FacturaController::class, 'deleteFactura'])->middleware([AdminCheck::class]);
});

