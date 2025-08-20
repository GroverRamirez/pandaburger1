<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\PedidoController;

// Rutas del módulo de pedidos
Route::prefix('pedidos')->name('pedidos.')->group(function () {
    // Lista de pedidos
    Route::get('/', function () {
        return Inertia::render('Pedidos/Index');
    })->name('index');

    // Crear nuevo pedido
    Route::get('/create', function () {
        return Inertia::render('Pedidos/Create');
    })->name('create');

    // Mostrar pedido específico
    Route::get('/{id}', function ($id) {
        return Inertia::render('Pedidos/Show', ['pedidoId' => $id]);
    })->name('show');

    // Editar pedido
    Route::get('/{id}/edit', function ($id) {
        return Inertia::render('Pedidos/Edit', ['pedidoId' => $id]);
    })->name('edit');
});

// Rutas de la API para pedidos
Route::prefix('api/pedidos')->group(function () {
    // Obtener todos los pedidos
    Route::get('/', [PedidoController::class, 'index']);
    
    // Obtener un pedido específico
    Route::get('/{id}', [PedidoController::class, 'show']);
    
    // Obtener pedido con detalles completos
    Route::get('/{id}/details', [PedidoController::class, 'showWithDetails']);
    
    // Crear nuevo pedido
    Route::post('/', [PedidoController::class, 'store']);
    
    // Actualizar pedido
    Route::put('/{id}', [PedidoController::class, 'update']);
    
    // Eliminar pedido
    Route::delete('/{id}', [PedidoController::class, 'destroy']);
    
    // Cambiar estado de pedido
    Route::patch('/{id}/estado', [PedidoController::class, 'changeEstado']);
    
    // Obtener estadísticas de pedidos
    Route::get('/stats', [PedidoController::class, 'getStats']);
    
    // Obtener estados de pedido
    Route::get('/estados', [PedidoController::class, 'getEstados']);
    
    // Obtener pedidos por cliente
    Route::get('/cliente/{clienteId}', [PedidoController::class, 'getByCliente']);
    
    // Obtener pedidos por fecha
    Route::get('/fecha/{fecha}', [PedidoController::class, 'getByDate']);
    
    // Obtener pedidos recientes
    Route::get('/recientes', [PedidoController::class, 'getRecientes']);
    
    // Obtener pedidos pendientes
    Route::get('/pendientes', [PedidoController::class, 'getPendientes']);
    
    // Obtener pedidos en proceso
    Route::get('/en-proceso', [PedidoController::class, 'getEnProceso']);
    
    // Obtener pedidos completados
    Route::get('/completados', [PedidoController::class, 'getCompletados']);
    
    // Obtener pedidos cancelados
    Route::get('/cancelados', [PedidoController::class, 'getCancelados']);
    
    // Exportar pedidos
    Route::get('/export', [PedidoController::class, 'export']);
    
    // Obtener timeline de pedido
    Route::get('/{id}/timeline', [PedidoController::class, 'getTimeline']);
    
    // Agregar comentario a pedido
    Route::post('/{id}/comentarios', [PedidoController::class, 'addComentario']);
    
    // Obtener productos disponibles para pedidos
    Route::get('/productos-disponibles', [PedidoController::class, 'getProductosDisponibles']);
    
    // Calcular total de pedido
    Route::post('/calcular-total', [PedidoController::class, 'calcularTotal']);
    
    // Validar stock de productos
    Route::post('/validar-stock', [PedidoController::class, 'validarStock']);
});
