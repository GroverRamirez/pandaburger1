<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class ClienteController extends Controller
{
    /**
     * Display a listing of clients.
     */
    public function index()
    {
        return Inertia::render('Clientes/Index');
    }

    /**
     * Show the form for creating a new client.
     */
    public function create()
    {
        return Inertia::render('Clientes/Create');
    }

    /**
     * Display the specified client.
     */
    public function show(Cliente $cliente)
    {
        return Inertia::render('Clientes/Show', [
            'cliente' => $cliente->load('pedidos.estado')
        ]);
    }

    /**
     * Show the form for editing the specified client.
     */
    public function edit(Cliente $cliente)
    {
        return Inertia::render('Clientes/Edit', [
            'cliente' => $cliente
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    /**
     * API: Get all clients with pagination and filtering
     */
    public function indexApi(Request $request): JsonResponse
    {
        $query = Cliente::query();

        // Apply search filter
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('nombre', 'like', "%{$search}%")
                  ->orWhere('correo_electronico', 'like', "%{$search}%");
        }

        // Apply sorting
        $sortBy = $request->input('sort_by', 'nombre');
        $sortOrder = $request->input('sort_order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        // Paginate results
        $perPage = $request->input('per_page', 10);
        $clientes = $query->paginate($perPage);

        return response()->json([
            'data' => $clientes->items(),
            'meta' => [
                'total' => $clientes->total(),
                'per_page' => $clientes->perPage(),
                'current_page' => $clientes->currentPage(),
                'last_page' => $clientes->lastPage(),
            ]
        ]);
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
     * API: Get client details
     */
    public function getClient(Cliente $cliente): JsonResponse
    {
        return response()->json($cliente->load('pedidos.estado'));
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
        return response()->json(['data' => $cliente->pedidos]);
    }
} 