<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PagoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $pagos = Pago::with(['pedido', 'metodoPago', 'estadoPago'])->get();
        return response()->json($pagos);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'pedido_id' => 'required|exists:pedidos,id',
            'monto' => 'required|numeric|min:0',
            'fecha' => 'required|date',
            'metodo_pago_id' => 'required|exists:metodos_pago,id',
            'estado_pago_id' => 'required|exists:estados_pago,id',
            'referencia_externa' => 'nullable|string|max:100',
        ]);

        $pago = Pago::create($request->all());
        $pago->load(['pedido', 'metodoPago', 'estadoPago']);
        return response()->json($pago, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Pago $pago): JsonResponse
    {
        $pago->load(['pedido', 'metodoPago', 'estadoPago']);
        return response()->json($pago);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pago $pago): JsonResponse
    {
        $request->validate([
            'pedido_id' => 'required|exists:pedidos,id',
            'monto' => 'required|numeric|min:0',
            'fecha' => 'required|date',
            'metodo_pago_id' => 'required|exists:metodos_pago,id',
            'estado_pago_id' => 'required|exists:estados_pago,id',
            'referencia_externa' => 'nullable|string|max:100',
        ]);

        $pago->update($request->all());
        $pago->load(['pedido', 'metodoPago', 'estadoPago']);
        return response()->json($pago);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pago $pago): JsonResponse
    {
        $pago->delete();
        return response()->json(null, 204);
    }

    /**
     * Get payments by order.
     */
    public function byOrder(int $pedidoId): JsonResponse
    {
        $pagos = Pago::where('pedido_id', $pedidoId)
            ->with(['metodoPago', 'estadoPago'])
            ->get();
        return response()->json($pagos);
    }

    /**
     * Get payments by status.
     */
    public function byStatus(int $estadoPagoId): JsonResponse
    {
        $pagos = Pago::where('estado_pago_id', $estadoPagoId)
            ->with(['pedido', 'metodoPago'])
            ->get();
        return response()->json($pagos);
    }

    /**
     * Get payments by method.
     */
    public function byMethod(int $metodoPagoId): JsonResponse
    {
        $pagos = Pago::where('metodo_pago_id', $metodoPagoId)
            ->with(['pedido', 'estadoPago'])
            ->get();
        return response()->json($pagos);
    }
} 