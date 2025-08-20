<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\DetallePedidoController;

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

// API v1 routes
Route::prefix('v1')->group(function () {
    // Clients routes
    Route::get('clientes', [ClienteController::class, 'indexApi']);
    Route::post('clientes', [ClienteController::class, 'store']);
    Route::get('clientes/{cliente}', [ClienteController::class, 'getClient']);
    Route::put('clientes/{cliente}', [ClienteController::class, 'update']);
    Route::delete('clientes/{cliente}', [ClienteController::class, 'destroy']);
    Route::get('clientes/{cliente}/orders', [ClienteController::class, 'orders']);

    // Orders routes - Specific routes first
    Route::get('pedidos/stats', [PedidoController::class, 'getStats']);
    Route::get('pedidos/estados', [PedidoController::class, 'getEstados']);
    Route::get('pedidos/estado/{estadoId}', [PedidoController::class, 'byStatus']);
    Route::get('pedidos/usuario/{usuarioId}', [PedidoController::class, 'byUser']);
    Route::get('pedidos/cliente/{clienteId}', [PedidoController::class, 'byClient']);
    Route::get('pedidos/fecha/{fecha}', [PedidoController::class, 'getByDate']);
    Route::get('pedidos/recientes', [PedidoController::class, 'getRecientes']);
    Route::get('pedidos/pendientes', [PedidoController::class, 'getPedidos']);
    Route::get('pedidos/en-proceso', [PedidoController::class, 'getEnProceso']);
    Route::get('pedidos/completados', [PedidoController::class, 'getCompletados']);
    Route::get('pedidos/cancelados', [PedidoController::class, 'getCancelados']);
    Route::post('pedidos/export', [PedidoController::class, 'export']);
    Route::get('pedidos/productos/disponibles', [PedidoController::class, 'getProductosDisponibles']);
    Route::post('pedidos/calcular-total', [PedidoController::class, 'calcularTotal']);
    Route::post('pedidos/validar-stock', [PedidoController::class, 'validarStock']);

    // Orders resource routes
    Route::apiResource('pedidos', PedidoController::class);

    // Order-specific routes that need to come after resource
    Route::get('pedidos/{pedido}/details', [PedidoController::class, 'showWithDetails']);
    Route::patch('pedidos/{pedido}/estado', [PedidoController::class, 'changeEstado']);
    Route::get('pedidos/{pedido}/timeline', [PedidoController::class, 'getTimeline']);
    Route::post('pedidos/{pedido}/comentario', [PedidoController::class, 'addComentario']);

    // Order details routes
    Route::apiResource('detalle-pedido', DetallePedidoController::class);
    Route::get('detalle-pedido/pedido/{pedidoId}', [DetallePedidoController::class, 'byOrder']);
    Route::get('detalle-pedido/producto/{productoId}', [DetallePedidoController::class, 'byProduct']);

    // Products routes
    Route::apiResource('productos', ProductoController::class);
    Route::get('productos/categoria/{categoriaId}', [ProductoController::class, 'byCategory']);

    // Categories routes
    Route::apiResource('categorias', CategoriaController::class);

    // Users routes
    Route::apiResource('usuarios', UsuarioController::class);
    Route::get('usuarios/rol/{rolId}', [UsuarioController::class, 'byRole']);

    // Roles routes
    Route::apiResource('roles', RolController::class);

    // Payments routes
    Route::apiResource('pagos', PagoController::class);
    Route::get('pagos/pedido/{pedidoId}', [PagoController::class, 'byOrder']);
    Route::get('pagos/estado/{estadoPagoId}', [PagoController::class, 'byStatus']);
    Route::get('pagos/metodo/{metodoPagoId}', [PagoController::class, 'byMethod']);
}); 