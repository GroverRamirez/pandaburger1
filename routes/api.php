<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RolController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\DetallePedidoController;
use App\Http\Controllers\PagoController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Roles routes
Route::apiResource('roles', RolController::class);

// Categories routes
Route::apiResource('categorias', CategoriaController::class);

// Products routes
Route::apiResource('productos', ProductoController::class);
Route::get('productos/categoria/{categoriaId}', [ProductoController::class, 'byCategory']);

// Users routes
Route::apiResource('usuarios', UsuarioController::class);
Route::get('usuarios/rol/{rolId}', [UsuarioController::class, 'byRole']);

// Clients routes
Route::apiResource('clientes', ClienteController::class);
Route::get('clientes/{cliente}/orders', [ClienteController::class, 'orders']);

// Orders routes
Route::apiResource('pedidos', PedidoController::class);
Route::get('pedidos/estado/{estadoId}', [PedidoController::class, 'byStatus']);
Route::get('pedidos/usuario/{usuarioId}', [PedidoController::class, 'byUser']);
Route::get('pedidos/cliente/{clienteId}', [PedidoController::class, 'byClient']);

// Order details routes
Route::apiResource('detalle-pedido', DetallePedidoController::class);
Route::get('detalle-pedido/pedido/{pedidoId}', [DetallePedidoController::class, 'byOrder']);
Route::get('detalle-pedido/producto/{productoId}', [DetallePedidoController::class, 'byProduct']);

// Payments routes
Route::apiResource('pagos', PagoController::class);
Route::get('pagos/pedido/{pedidoId}', [PagoController::class, 'byOrder']);
Route::get('pagos/estado/{estadoPagoId}', [PagoController::class, 'byStatus']);
Route::get('pagos/metodo/{metodoPagoId}', [PagoController::class, 'byMethod']); 