<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Producto;
use Illuminate\Support\Facades\DB;

class CleanDuplicateProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'productos:clean-duplicates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean duplicate products, keeping the ones with images';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Cleaning duplicate products...');

        // Buscar productos duplicados por nombre
        $duplicates = DB::table('productos')
            ->select('nombre', DB::raw('COUNT(*) as count'))
            ->groupBy('nombre')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        $totalCleaned = 0;

        foreach ($duplicates as $duplicate) {
            $this->info("Processing: {$duplicate->nombre} ({$duplicate->count} duplicates)");

            // Obtener todos los productos con este nombre
            $productos = Producto::where('nombre', $duplicate->nombre)->get();

            // Encontrar el mejor producto (con imagen si existe)
            $bestProduct = $productos->sortByDesc(function ($producto) {
                return $producto->imagen_url ? 1 : 0;
            })->first();

            // Eliminar los demás
            $toDelete = $productos->where('id', '!=', $bestProduct->id);
            
            foreach ($toDelete as $product) {
                $this->line("  Deleting duplicate: ID {$product->id}");
                $product->delete();
                $totalCleaned++;
            }
        }

        $this->info("Cleaned {$totalCleaned} duplicate products.");
        
        // Mostrar resumen final
        $totalProducts = Producto::count();
        $this->info("Total products remaining: {$totalProducts}");
        
        return 0;
    }
}