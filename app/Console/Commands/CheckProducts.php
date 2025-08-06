<?php

namespace App\Console\Commands;

use App\Models\Producto;
use Illuminate\Console\Command;

class CheckProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check products and their images';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Verificando productos...');
        
        $productos = Producto::all(['id', 'nombre', 'imagen_url', 'precio']);
        
        if ($productos->isEmpty()) {
            $this->warn('No hay productos en la base de datos.');
            return;
        }
        
        $this->info("Total de productos: {$productos->count()}");
        $this->newLine();
        
        $productos->each(function ($producto) {
            $imagenStatus = $producto->imagen_url ? '✅ Con imagen' : '❌ Sin imagen';
            $this->line("ID: {$producto->id} | Nombre: {$producto->nombre} | Precio: \${$producto->precio} | {$imagenStatus}");
            
            if ($producto->imagen_url) {
                $this->line("  URL de imagen: {$producto->imagen_url}");
            }
        });
        
        $conImagen = $productos->whereNotNull('imagen_url')->count();
        $sinImagen = $productos->whereNull('imagen_url')->count();
        
        $this->newLine();
        $this->info("Resumen:");
        $this->line("Productos con imagen: {$conImagen}");
        $this->line("Productos sin imagen: {$sinImagen}");
    }
}
