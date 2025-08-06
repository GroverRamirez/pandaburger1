<?php

namespace Database\Seeders;

use App\Models\Permiso;
use Illuminate\Database\Seeder;

class PermisoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permisos = [
            ['nombre' => 'Ver usuarios', 'descripcion' => 'Permite ver la lista de usuarios'],
            ['nombre' => 'Crear usuarios', 'descripcion' => 'Permite crear nuevos usuarios'],
            ['nombre' => 'Editar usuarios', 'descripcion' => 'Permite editar usuarios existentes'],
            ['nombre' => 'Eliminar usuarios', 'descripcion' => 'Permite eliminar usuarios'],
            ['nombre' => 'Gestionar roles', 'descripcion' => 'Permite gestionar roles y permisos'],
            ['nombre' => 'Ver productos', 'descripcion' => 'Permite ver la lista de productos'],
            ['nombre' => 'Crear productos', 'descripcion' => 'Permite crear nuevos productos'],
            ['nombre' => 'Editar productos', 'descripcion' => 'Permite editar productos existentes'],
            ['nombre' => 'Eliminar productos', 'descripcion' => 'Permite eliminar productos'],
            ['nombre' => 'Gestionar pedidos', 'descripcion' => 'Permite gestionar pedidos'],
            ['nombre' => 'Ver reportes', 'descripcion' => 'Permite ver reportes del sistema'],
            ['nombre' => 'Configuración del sistema', 'descripcion' => 'Permite configurar el sistema'],
        ];

        foreach ($permisos as $permiso) {
            Permiso::create($permiso);
        }
    }
}
