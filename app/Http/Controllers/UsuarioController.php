<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $usuarios = Usuario::with('rol')->get();
        return response()->json($usuarios);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'rol_id' => 'required|exists:roles,id',
            'nombre' => 'required|string|max:60',
            'correo_electronico' => 'required|email|max:120|unique:users',
            'telefono' => 'nullable|string|max:30',
            'password_hash' => 'required|string|min:6',
        ]);

        $data = $request->all();
        $data['password_hash'] = Hash::make($data['password_hash']);

        $usuario = Usuario::create($data);
        $usuario->load('rol');
        return response()->json($usuario, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Usuario $usuario): JsonResponse
    {
        $usuario->load('rol');
        return response()->json($usuario);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Usuario $usuario): JsonResponse
    {
        $request->validate([
            'rol_id' => 'required|exists:roles,id',
            'nombre' => 'required|string|max:60',
            'correo_electronico' => 'required|email|max:120|unique:users,correo_electronico,' . $usuario->id,
            'telefono' => 'nullable|string|max:30',
            'password_hash' => 'nullable|string|min:6',
        ]);

        $data = $request->all();
        if (isset($data['password_hash'])) {
            $data['password_hash'] = Hash::make($data['password_hash']);
        }

        $usuario->update($data);
        $usuario->load('rol');
        return response()->json($usuario);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Usuario $usuario): JsonResponse
    {
        $usuario->delete();
        return response()->json(null, 204);
    }

    /**
     * Get users by role.
     */
    public function byRole(int $rolId): JsonResponse
    {
        $usuarios = Usuario::where('rol_id', $rolId)->with('rol')->get();
        return response()->json($usuarios);
    }
} 