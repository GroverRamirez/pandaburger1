<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pedido;
use App\Models\DetallePedido;
use App\Models\Cliente;
use App\Models\Producto;
use App\Models\User;
use App\Models\EstadoPedido;
use Carbon\Carbon;

class PedidoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener datos necesarios
        $clientes = Cliente::all();
        $productos = Producto::all();
        $usuarios = User::all();
        $estados = EstadoPedido::all();

        if ($clientes->isEmpty() || $productos->isEmpty() || $usuarios->isEmpty() || $estados->isEmpty()) {
            $this->command->warn('No se pueden crear pedidos: faltan clientes, productos, usuarios o estados');
            return;
        }

        // Crear pedidos de ejemplo
        for ($i = 1; $i <= 15; $i++) {
            $cliente = $clientes->random();
            $usuario = $usuarios->random();
            $estado = $estados->random();
            
            // Fecha aleatoria en los últimos 30 días
            $fecha = Carbon::now()->subDays(rand(0, 30))->subHours(rand(0, 23))->subMinutes(rand(0, 59));

            $pedido = Pedido::create([
                'usuario_id' => $usuario->id,
                'cliente_id' => $cliente->id,
                'estado_id' => $estado->id,
                'fecha' => $fecha,
                'created_at' => $fecha,
                'updated_at' => $fecha
            ]);

            // Crear detalles del pedido (1-5 productos)
            $numProductos = rand(1, 5);
            $productosSeleccionados = $productos->random($numProductos);

            foreach ($productosSeleccionados as $producto) {
                $cantidad = rand(1, 3);
                $precioUnitario = $producto->precio;
                $precioTotal = $cantidad * $precioUnitario;

                DetallePedido::create([
                    'pedido_id' => $pedido->id,
                    'producto_id' => $producto->id,
                    'cantidad' => $cantidad,
                    'precio_unitario' => $precioUnitario,
                    'precio_total' => $precioTotal,
                    'created_at' => $fecha,
                    'updated_at' => $fecha
                ]);

                // Actualizar stock del producto
                $producto->decrement('stock_disponible', $cantidad);
            }

            // Crear algunos pedidos con estados específicos para mostrar variedad
            if ($i <= 3) {
                $pedido->update(['estado_id' => 1]); // Pendiente
            } elseif ($i <= 6) {
                $pedido->update(['estado_id' => 2]); // En Proceso
            } elseif ($i <= 10) {
                $pedido->update(['estado_id' => 3]); // Completado
            } elseif ($i <= 12) {
                $pedido->update(['estado_id' => 4]); // Cancelado
            } else {
                $pedido->update(['estado_id' => 5]); // Entregado
            }
        }

        $this->command->info('15 pedidos de ejemplo creados exitosamente');
    }
}
