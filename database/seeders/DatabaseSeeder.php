<?php

namespace Database\Seeders;

use App\Models\Usuario;
use App\Models\Producto;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call all seeders in order
        $this->call([
            RolSeeder::class,
            EstadoPedidoSeeder::class,
            EstadoPagoSeeder::class,
            MetodoPagoSeeder::class,
            CategoriaSeeder::class,
        ]);

        // Create a default admin user
        Usuario::create([
            'rol_id' => 1, // Administrador
            'nombre' => 'Administrador',
            'correo_electronico' => 'admin@pandaburger.com',
            'password_hash' => bcrypt('password'),
        ]);

        // Create sample products
        Producto::create([
            'nombre' => 'Hamburguesa Clásica',
            'descripcion' => 'Hamburguesa con carne, lechuga, tomate y queso',
            'precio' => 8.99,
            'categoria_id' => 1, // Hamburguesas
        ]);

        Producto::create([
            'nombre' => 'Hamburguesa Doble',
            'descripcion' => 'Hamburguesa con doble carne, lechuga, tomate y queso',
            'precio' => 12.99,
            'categoria_id' => 1, // Hamburguesas
        ]);

        Producto::create([
            'nombre' => 'Coca Cola',
            'descripcion' => 'Bebida gaseosa Coca Cola 500ml',
            'precio' => 2.50,
            'categoria_id' => 2, // Bebidas
        ]);

        Producto::create([
            'nombre' => 'Papas Fritas',
            'descripcion' => 'Porción de papas fritas crujientes',
            'precio' => 3.99,
            'categoria_id' => 3, // Acompañamientos
        ]);
    }
}
