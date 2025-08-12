<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Producto;
use App\Models\Categoria;

class ProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get categories
        $hamburguesas = Categoria::where('nombre', 'Hamburguesas')->first();
        $bebidas = Categoria::where('nombre', 'Bebidas')->first();
        $acompañamientos = Categoria::where('nombre', 'Acompañamientos')->first();

        // Create sample products
        $productos = [
            [
                'nombre' => 'Hamburguesa Clásica',
                'descripcion' => 'Hamburguesa con carne, lechuga, tomate y queso',
                'precio' => 12.00,
                'categoria_id' => $hamburguesas ? $hamburguesas->id : 1,
                'imagen_url' => 'https://images.unsplash.com/photo-1551782450-a2132b4ba21d?w=500&h=500&fit=crop&auto=format&q=80',
            ],
            [
                'nombre' => 'Hamburguesa Doble',
                'descripcion' => 'Hamburguesa con doble carne y queso',
                'precio' => 20.00,
                'categoria_id' => $hamburguesas ? $hamburguesas->id : 1,
                'imagen_url' => 'https://images.unsplash.com/photo-1572802419224-296b0aeee0d9?w=500&h=500&fit=crop&auto=format&q=80',
            ],
            [
                'nombre' => 'Coca Cola',
                'descripcion' => 'Bebida gaseosa Coca Cola 500ml',
                'precio' => 7.50,
                'categoria_id' => $bebidas ? $bebidas->id : 2,
                'imagen_url' => 'https://images.unsplash.com/photo-1622483767028-3f66f32aef97?w=500&h=500&fit=crop&auto=format&q=80',
            ],
            [
                'nombre' => 'Papas Fritas',
                'descripcion' => 'Papas fritas crujientes con sal',
                'precio' => 5.50,
                'categoria_id' => $acompañamientos ? $acompañamientos->id : 3,
                'imagen_url' => 'https://images.unsplash.com/photo-1630384060421-cb20d0e0649d?w=500&h=500&fit=crop&auto=format&q=80',
            ],
            [
                'nombre' => 'Agua Mineral',
                'descripcion' => 'Agua mineral sin gas 500ml',
                'precio' => 6.50,
                'categoria_id' => $bebidas ? $bebidas->id : 2,
                'imagen_url' => 'https://images.unsplash.com/photo-1559827260-dc66d52bef19?w=500&h=500&fit=crop&auto=format&q=80',
            ],
            [
                'nombre' => 'Hamburguesa BBQ',
                'descripcion' => 'Hamburguesa con salsa BBQ, cebolla caramelizada y bacon',
                'precio' => 18.50,
                'categoria_id' => $hamburguesas ? $hamburguesas->id : 1,
                'imagen_url' => 'https://images.unsplash.com/photo-1550317138-10000687a72b?w=500&h=500&fit=crop&auto=format&q=80',
            ],
            [
                'nombre' => 'Nuggets de Pollo',
                'descripcion' => 'Tiernos nuggets de pollo con salsa a elegir',
                'precio' => 12.50,
                'categoria_id' => $acompañamientos ? $acompañamientos->id : 3,
                'imagen_url' => 'https://images.unsplash.com/photo-1562967914-608f82629710?w=500&h=500&fit=crop&auto=format&q=80',
            ],
            [
                'nombre' => 'Sprite',
                'descripcion' => 'Refrescante bebida de lima-limón 500ml',
                'precio' => 7.50,
                'categoria_id' => $bebidas ? $bebidas->id : 2,
                'imagen_url' => 'https://images.unsplash.com/photo-1624517452488-04869289c4ca?w=500&h=500&fit=crop&auto=format&q=80',
            ],
            [
                'nombre' => 'Anillos de Cebolla',
                'descripcion' => 'Crujientes anillos de cebolla empanizados',
                'precio' => 8.50,
                'categoria_id' => $acompañamientos ? $acompañamientos->id : 3,
                'imagen_url' => 'https://images.unsplash.com/photo-1639024471283-03518883512d?w=500&h=500&fit=crop&auto=format&q=80',
            ],
        ];

        foreach ($productos as $producto) {
            Producto::updateOrCreate(
                ['nombre' => $producto['nombre']], // Buscar por nombre
                $producto // Datos a crear o actualizar
            );
        }
    }
} 