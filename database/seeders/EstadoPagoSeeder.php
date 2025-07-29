<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\EstadoPago;

class EstadoPagoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $estados = [
            ['nombre' => 'Pendiente'],
            ['nombre' => 'Completado'],
            ['nombre' => 'Fallido'],
            ['nombre' => 'Reembolsado'],
        ];

        foreach ($estados as $estado) {
            EstadoPago::create($estado);
        }
    }
} 