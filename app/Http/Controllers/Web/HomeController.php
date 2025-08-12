<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    /**
     * Display the home page with products organized by category.
     */
    public function index(): Response
    {
        // Get all categories with their products
        $categorias = Categoria::with(['productos' => function ($query) {
            $query->orderBy('nombre');
        }])->orderBy('nombre')->get();

        // Get featured products (first 6 products)
        $productosDestacados = Producto::with('categoria')
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        return Inertia::render('Home', [
            'categorias' => $categorias,
            'productosDestacados' => $productosDestacados,
            'canLogin' => \Route::has('login'),
            'canRegister' => \Route::has('register'),
        ]);
    }

    /**
     * Get products by category for AJAX requests.
     */
    public function productosPorCategoria(int $categoriaId)
    {
        $productos = Producto::where('categoria_id', $categoriaId)
            ->with('categoria')
            ->orderBy('nombre')
            ->get();

        return response()->json($productos);
    }
}
