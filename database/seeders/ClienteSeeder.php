<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Cliente;
use Illuminate\Support\Facades\Hash;

class ClienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clientes = [
            [
                'nombre' => 'Juan Pérez',
                'correo_electronico' => 'juan.perez@email.com',
                'telefono' => '+591 70012345',
                'direccion' => 'Av. Principal 123, La Paz',
                'password_hash' => Hash::make('password123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'María García',
                'correo_electronico' => 'maria.garcia@email.com',
                'telefono' => '+591 70054321',
                'direccion' => 'Calle Comercio 456, Santa Cruz',
                'password_hash' => Hash::make('password123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Carlos López',
                'correo_electronico' => 'carlos.lopez@email.com',
                'telefono' => '+591 70098765',
                'direccion' => 'Plaza Mayor 789, Cochabamba',
                'password_hash' => Hash::make('password123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Ana Rodríguez',
                'correo_electronico' => 'ana.rodriguez@email.com',
                'telefono' => '+591 70011111',
                'direccion' => 'Zona Sur 321, La Paz',
                'password_hash' => Hash::make('password123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Luis Martínez',
                'correo_electronico' => 'luis.martinez@email.com',
                'telefono' => '+591 70022222',
                'direccion' => 'Av. Libertador 654, Santa Cruz',
                'password_hash' => Hash::make('password123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($clientes as $cliente) {
            Cliente::create($cliente);
        }
    }
}
