<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $clientes = Cliente::all();
        return response()->json($clientes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'nombre' => 'required|string|max:60',
            'correo_electronico' => 'nullable|email|max:120|unique:clientes',
            'telefono' => 'nullable|string|max:30',
            'direccion' => 'nullable|string|max:120',
            'password_hash' => 'nullable|string|min:6',
        ]);

        $data = $request->all();
        if (isset($data['password_hash'])) {
            $data['password_hash'] = Hash::make($data['password_hash']);
        }

        $cliente = Cliente::create($data);
        return response()->json($cliente, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Cliente $cliente): JsonResponse
    {
        return response()->json($cliente);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cliente $cliente): JsonResponse
    {
        $request->validate([
            'nombre' => 'required|string|max:60',
            'correo_electronico' => 'nullable|email|max:120|unique:clientes,correo_electronico,' . $cliente->id,
            'telefono' => 'nullable|string|max:30',
            'direccion' => 'nullable|string|max:120',
            'password_hash' => 'nullable|string|min:6',
        ]);

        $data = $request->all();
        if (isset($data['password_hash'])) {
            $data['password_hash'] = Hash::make($data['password_hash']);
        }

        $cliente->update($data);
        return response()->json($cliente);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cliente $cliente): JsonResponse
    {
        $cliente->delete();
        return response()->json(null, 204);
    }

    /**
     * Get client orders.
     */
    public function orders(Cliente $cliente): JsonResponse
    {
        $cliente->load('pedidos.estado');
        return response()->json($cliente->pedidos);
    }
} 