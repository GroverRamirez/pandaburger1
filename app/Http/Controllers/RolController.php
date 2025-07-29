<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class RolController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $roles = Rol::all();
        return response()->json($roles);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'nombre' => 'required|string|max:40',
        ]);

        $rol = Rol::create($request->all());
        return response()->json($rol, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Rol $rol): JsonResponse
    {
        return response()->json($rol);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Rol $rol): JsonResponse
    {
        $request->validate([
            'nombre' => 'required|string|max:40',
        ]);

        $rol->update($request->all());
        return response()->json($rol);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rol $rol): JsonResponse
    {
        $rol->delete();
        return response()->json(null, 204);
    }
} 