<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\EstadoPedido;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PedidoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $pedidos = Pedido::with(['usuario', 'cliente', 'estado', 'detallesPedido.producto'])->get();
        return response()->json($pedidos);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'usuario_id' => 'nullable|exists:users,id',
            'cliente_id' => 'nullable|exists:clientes,id',
            'estado_id' => 'required|exists:estados_pedido,id',
            'fecha' => 'required|date',
        ]);

        $pedido = Pedido::create($request->all());
        $pedido->load(['usuario', 'cliente', 'estado']);
        return response()->json($pedido, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Pedido $pedido): JsonResponse
    {
        $pedido->load(['usuario', 'cliente', 'estado', 'detallesPedido.producto', 'pagos']);
        return response()->json($pedido);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pedido $pedido): JsonResponse
    {
        $request->validate([
            'usuario_id' => 'nullable|exists:users,id',
            'cliente_id' => 'nullable|exists:clientes,id',
            'estado_id' => 'required|exists:estados_pedido,id',
            'fecha' => 'required|date',
        ]);

        $pedido->update($request->all());
        $pedido->load(['usuario', 'cliente', 'estado']);
        return response()->json($pedido);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pedido $pedido): JsonResponse
    {
        $pedido->delete();
        return response()->json(null, 204);
    }

    /**
     * Get orders by status.
     */
    public function byStatus(int $estadoId): JsonResponse
    {
        $pedidos = Pedido::where('estado_id', $estadoId)
            ->with(['usuario', 'cliente', 'estado'])
            ->get();
        return response()->json($pedidos);
    }

    /**
     * Get orders by user.
     */
    public function byUser(int $usuarioId): JsonResponse
    {
        $pedidos = Pedido::where('usuario_id', $usuarioId)
            ->with(['cliente', 'estado', 'detallesPedido.producto'])
            ->get();
        return response()->json($pedidos);
    }

    /**
     * Get orders by client.
     */
    public function byClient(int $clienteId): JsonResponse
    {
        $pedidos = Pedido::where('cliente_id', $clienteId)
            ->with(['usuario', 'estado', 'detallesPedido.producto'])
            ->get();
        return response()->json($pedidos);
    }
} 