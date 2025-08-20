<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\EstadoPedido;

class EstadoPedidoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $estados = [
            [
                'id' => 1,
                'nombre' => 'Pendiente',
                'color' => '#F59E0B',
                'icon' => 'clock'
            ],
            [
                'id' => 2,
                'nombre' => 'En Proceso',
                'color' => '#3B82F6',
                'icon' => 'alert-circle'
            ],
            [
                'id' => 3,
                'nombre' => 'Completado',
                'color' => '#10B981',
                'icon' => 'check-circle'
            ],
            [
                'id' => 4,
                'nombre' => 'Cancelado',
                'color' => '#EF4444',
                'icon' => 'x-circle'
            ],
            [
                'id' => 5,
                'nombre' => 'Entregado',
                'color' => '#8B5CF6',
                'icon' => 'truck'
            ]
        ];

        foreach ($estados as $estado) {
            EstadoPedido::updateOrCreate(
                ['id' => $estado['id']],
                [
                    'nombre' => $estado['nombre'],
                    'color' => $estado['color'],
                    'icon' => $estado['icon']
                ]
            );
        }
    }
} 