<?php

use App\Http\Controllers\Web\ProductoController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::resource('productos', ProductoController::class);
}); 