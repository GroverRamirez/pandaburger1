<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Rutas del módulo de pedidos (Vistas Web para Inertia)
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
