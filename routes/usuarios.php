<?php

use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\RolController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Rutas de Usuarios
Route::middleware(['auth'])->group(function () {
    // Gestión de Usuarios
    Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
    Route::get('/usuarios/create', [UsuarioController::class, 'create'])->name('usuarios.create');
    Route::post('/usuarios', [UsuarioController::class, 'store'])->name('usuarios.store');
    Route::get('/usuarios/{usuario}', [UsuarioController::class, 'show'])->name('usuarios.show');
    Route::get('/usuarios/{usuario}/edit', [UsuarioController::class, 'edit'])->name('usuarios.edit');
    Route::put('/usuarios/{usuario}', [UsuarioController::class, 'update'])->name('usuarios.update');
    Route::delete('/usuarios/{usuario}', [UsuarioController::class, 'destroy'])->name('usuarios.destroy');
    Route::patch('/usuarios/{usuario}/restore', [UsuarioController::class, 'restore'])->name('usuarios.restore');

    // Gestión de Roles
    Route::get('/roles', [RolController::class, 'index'])->name('roles.index');
    Route::get('/roles/create', [RolController::class, 'create'])->name('roles.create');
    Route::post('/roles', [RolController::class, 'store'])->name('roles.store');
    Route::get('/roles/{rol}', [RolController::class, 'show'])->name('roles.show');
    Route::get('/roles/{rol}/edit', [RolController::class, 'edit'])->name('roles.edit');
    Route::put('/roles/{rol}', [RolController::class, 'update'])->name('roles.update');
    Route::delete('/roles/{rol}', [RolController::class, 'destroy'])->name('roles.destroy');

    // Permisos de Roles
Route::put('/roles/{rol}/permisos', [RolController::class, 'updatePermissions'])->name('roles.permissions.update');
Route::get('/roles/{rol}/permisos', [RolController::class, 'getPermissions'])->name('roles.permissions.get');
Route::post('/roles/{rol}/permisos/assign', [RolController::class, 'assignPermissions'])->name('roles.permissions.assign');
Route::delete('/roles/{rol}/permisos/remove', [RolController::class, 'removePermissions'])->name('roles.permissions.remove');

// Obtener todos los permisos
Route::get('/permisos', [RolController::class, 'getAllPermisos'])->name('permisos.index');

    // Perfil de Usuario
    Route::get('/profile', [UsuarioController::class, 'profile'])->name('profile');

    // API Routes para el frontend
    Route::prefix('api')->group(function () {
        // Usuarios API
        Route::get('/profile', [UsuarioController::class, 'profile'])->name('api.profile');
        Route::put('/profile', [UsuarioController::class, 'updateProfile'])->name('api.profile.update');
        Route::put('/profile/password', [UsuarioController::class, 'updatePassword'])->name('api.profile.password');
        Route::get('/usuarios/statistics', [UsuarioController::class, 'statistics'])->name('api.usuarios.statistics');

        // Roles API
        Route::get('/roles', [RolController::class, 'apiIndex'])->name('api.roles.index');
        Route::get('/roles/statistics', [RolController::class, 'statistics'])->name('api.roles.statistics');

        // Permisos API (asumiendo que tienes un controlador de permisos)
        Route::get('/permisos', function () {
            // Aquí deberías retornar los permisos disponibles
            return response()->json([
                'permisos' => [
                    ['id' => 1, 'nombre' => 'Ver usuarios', 'descripcion' => 'Permite ver la lista de usuarios'],
                    ['id' => 2, 'nombre' => 'Crear usuarios', 'descripcion' => 'Permite crear nuevos usuarios'],
                    ['id' => 3, 'nombre' => 'Editar usuarios', 'descripcion' => 'Permite editar usuarios existentes'],
                    ['id' => 4, 'nombre' => 'Eliminar usuarios', 'descripcion' => 'Permite eliminar usuarios'],
                    ['id' => 5, 'nombre' => 'Gestionar roles', 'descripcion' => 'Permite gestionar roles y permisos'],
                    ['id' => 6, 'nombre' => 'Ver productos', 'descripcion' => 'Permite ver la lista de productos'],
                    ['id' => 7, 'nombre' => 'Crear productos', 'descripcion' => 'Permite crear nuevos productos'],
                    ['id' => 8, 'nombre' => 'Editar productos', 'descripcion' => 'Permite editar productos existentes'],
                    ['id' => 9, 'nombre' => 'Eliminar productos', 'descripcion' => 'Permite eliminar productos'],
                    ['id' => 10, 'nombre' => 'Gestionar pedidos', 'descripcion' => 'Permite gestionar pedidos'],
                    ['id' => 11, 'nombre' => 'Ver reportes', 'descripcion' => 'Permite ver reportes del sistema'],
                    ['id' => 12, 'nombre' => 'Configuración del sistema', 'descripcion' => 'Permite configurar el sistema'],
                ]
            ]);
        })->name('api.permisos.index');
    });
}); 