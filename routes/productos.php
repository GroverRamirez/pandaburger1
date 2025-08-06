<?php

use App\Http\Controllers\Web\ProductoController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::resource('productos', ProductoController::class);
    Route::post('productos/{producto}/upload-image', [ProductoController::class, 'uploadImage'])->name('productos.upload-image');
}); 