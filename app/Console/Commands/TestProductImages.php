<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Producto;
use Illuminate\Support\Facades\Http;

class TestProductImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'productos:test-images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test product image URLs accessibility';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing product images...');

        $productos = Producto::all();
        $totalProducts = $productos->count();
        $workingImages = 0;
        $brokenImages = 0;

        foreach ($productos as $producto) {
            $this->line("Testing: {$producto->nombre}");
            
            if (!$producto->imagen_url) {
                $this->warn("  No image URL");
                continue;
            }

            try {
                $response = Http::timeout(10)->head($producto->imagen_url);
                
                if ($response->successful()) {
                    $this->info("  ✓ Image accessible ({$response->status()})");
                    $workingImages++;
                } else {
                    $this->error("  ✗ Image not accessible ({$response->status()})");
                    $this->line("    URL: {$producto->imagen_url}");
                    $brokenImages++;
                }
            } catch (\Exception $e) {
                $this->error("  ✗ Error accessing image: " . $e->getMessage());
                $this->line("    URL: {$producto->imagen_url}");
                $brokenImages++;
            }
        }

        $this->newLine();
        $this->info("Summary:");
        $this->line("Total products: {$totalProducts}");
        $this->line("Working images: {$workingImages}");
        $this->line("Broken images: {$brokenImages}");
        
        if ($brokenImages > 0) {
            $this->warn("Some images are not accessible. Consider updating the URLs.");
        } else {
            $this->info("All images are accessible!");
        }

        return $brokenImages > 0 ? 1 : 0;
    }
}