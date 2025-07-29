<?php

namespace App\Http\Controllers;

use App\Models\DetallePedido;
use App\Models\Pedido;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class DetallePedidoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $detallesPedido = DetallePedido::with(['pedido', 'producto'])->get();
        return response()->json($detallesPedido);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'pedido_id' => 'required|exists:pedidos,id',
            'producto_id' => 'required|exists:productos,id',
            'cantidad' => 'required|integer|min:1',
            'precio_unitario' => 'required|numeric|min:0',
            'precio_total' => 'required|numeric|min:0',
        ]);

        $detallePedido = DetallePedido::create($request->all());
        $detallePedido->load(['pedido', 'producto']);
        return response()->json($detallePedido, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(DetallePedido $detallePedido): JsonResponse
    {
        $detallePedido->load(['pedido', 'producto']);
        return response()->json($detallePedido);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DetallePedido $detallePedido): JsonResponse
    {
        $request->validate([
            'pedido_id' => 'required|exists:pedidos,id',
            'producto_id' => 'required|exists:productos,id',
            'cantidad' => 'required|integer|min:1',
            'precio_unitario' => 'required|numeric|min:0',
            'precio_total' => 'required|numeric|min:0',
        ]);

        $detallePedido->update($request->all());
        $detallePedido->load(['pedido', 'producto']);
        return response()->json($detallePedido);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DetallePedido $detallePedido): JsonResponse
    {
        $detallePedido->delete();
        return response()->json(null, 204);
    }

    /**
     * Get order details by order.
     */
    public function byOrder(int $pedidoId): JsonResponse
    {
        $detallesPedido = DetallePedido::where('pedido_id', $pedidoId)
            ->with(['producto'])
            ->get();
        return response()->json($detallesPedido);
    }

    /**
     * Get order details by product.
     */
    public function byProduct(int $productoId): JsonResponse
    {
        $detallesPedido = DetallePedido::where('producto_id', $productoId)
            ->with(['pedido'])
            ->get();
        return response()->json($detallesPedido);
    }
} 