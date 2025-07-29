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
                'precio' => 8.50,
                'categoria_id' => $hamburguesas ? $hamburguesas->id : 1,
            ],
            [
                'nombre' => 'Hamburguesa Doble',
                'descripcion' => 'Hamburguesa con doble carne y queso',
                'precio' => 12.00,
                'categoria_id' => $hamburguesas ? $hamburguesas->id : 1,
            ],
            [
                'nombre' => 'Coca Cola',
                'descripcion' => 'Bebida gaseosa Coca Cola 500ml',
                'precio' => 2.50,
                'categoria_id' => $bebidas ? $bebidas->id : 2,
            ],
            [
                'nombre' => 'Papas Fritas',
                'descripcion' => 'Papas fritas crujientes con sal',
                'precio' => 3.50,
                'categoria_id' => $acompañamientos ? $acompañamientos->id : 3,
            ],
            [
                'nombre' => 'Agua Mineral',
                'descripcion' => 'Agua mineral sin gas 500ml',
                'precio' => 1.50,
                'categoria_id' => $bebidas ? $bebidas->id : 2,
            ],
        ];

        foreach ($productos as $producto) {
            Producto::create($producto);
        }
    }
} 