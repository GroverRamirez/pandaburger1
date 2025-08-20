<?php

use App\Http\Controllers\ClienteController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Rutas de Clientes (Vistas Web para Inertia)
// Route::middleware(['auth'])->group(function () { // Comentado temporalmente para testing
    
    // Vista principal de clientes
    Route::get('/clientes', [ClienteController::class, 'index'])
        ->name('clientes.index');

    // Crear cliente
    Route::get('/clientes/create', [ClienteController::class, 'create'])
        ->name('clientes.create');

    // Ver perfil de cliente
    Route::get('/clientes/{cliente}', [ClienteController::class, 'show'])
        ->name('clientes.show');

    // Editar cliente
    Route::get('/clientes/{cliente}/edit', [ClienteController::class, 'edit'])
        ->name('clientes.edit');

// }); // Comentado temporalmente para testing
