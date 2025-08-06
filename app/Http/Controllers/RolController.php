<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use App\Models\Permiso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class RolController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Rol::with(['permisos', 'usuarios'])
            ->withCount('usuarios')
            ->orderBy('created_at', 'desc')
            ->get();

        $permisos = Permiso::orderBy('nombre')->get();

        return Inertia::render('Usuarios/Roles', [
            'roles' => $roles,
            'permisos' => $permisos,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permisos = Permiso::all();
        
        return Inertia::render('Usuarios/Roles/Create', [
            'permisos' => $permisos,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:50|unique:roles,nombre',
            'descripcion' => 'nullable|string|max:255',
            'permisos' => 'array',
            'permisos.*' => 'exists:permisos,id',
        ]);

        DB::beginTransaction();
        try {
            $rol = Rol::create([
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
            ]);

            if ($request->has('permisos')) {
                $rol->permisos()->attach($request->permisos);
            }

            DB::commit();

            return redirect()->route('roles.index')
                ->with('success', 'Rol creado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Rol $rol)
    {
        $rol->load(['permisos', 'usuarios']);

        return Inertia::render('Usuarios/Roles/Show', [
            'rol' => $rol,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Rol $rol)
    {
        $permisos = Permiso::all();
        $rol->load('permisos');
        
        return Inertia::render('Usuarios/Roles/Edit', [
            'rol' => $rol,
            'permisos' => $permisos,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Rol $rol)
    {
        $request->validate([
            'nombre' => 'required|string|max:50|unique:roles,nombre,' . $rol->id,
            'descripcion' => 'nullable|string|max:255',
            'permisos' => 'array',
            'permisos.*' => 'exists:permisos,id',
        ]);

        DB::beginTransaction();
        try {
            $rol->update([
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
            ]);

            // Sync permissions
            $rol->permisos()->sync($request->permisos ?? []);

            DB::commit();

            return redirect()->route('roles.index')
                ->with('success', 'Rol actualizado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rol $rol)
    {
        // Check if role has users
        if ($rol->usuarios()->count() > 0) {
            return back()->with('error', 'No se puede eliminar un rol que tiene usuarios asignados.');
        }

        DB::beginTransaction();
        try {
            // Remove role permissions
            $rol->permisos()->detach();
            
            // Delete role
            $rol->delete();

            DB::commit();

            return redirect()->route('roles.index')
                ->with('success', 'Rol eliminado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Update role permissions.
     */
    public function updatePermissions(Request $request, Rol $rol)
    {
        $request->validate([
            'permisos' => 'array',
            'permisos.*' => 'exists:permisos,id',
        ]);

        $rol->permisos()->sync($request->permisos ?? []);

        return response()->json([
            'message' => 'Permisos actualizados exitosamente.',
            'rol' => $rol->fresh()->load('permisos'),
        ]);
    }

    /**
     * Get all roles for API.
     */
    public function apiIndex()
    {
        $roles = Rol::with('permisos')
            ->withCount('usuarios')
            ->orderBy('nombre')
            ->get();

        return Inertia::render('Usuarios/Index', [
            'roles' => $roles,
        ]);
    }

    /**
     * Get role statistics.
     */
    public function statistics()
    {
        $stats = [
            'total_roles' => Rol::count(),
            'roles_with_users' => Rol::has('usuarios')->count(),
            'roles_with_permissions' => Rol::has('permisos')->count(),
            'users_by_role' => Rol::withCount('usuarios')
                ->orderBy('usuarios_count', 'desc')
                ->get()
                ->map(function ($rol) {
                    return [
                        'nombre' => $rol->nombre,
                        'count' => $rol->usuarios_count,
                    ];
                }),
        ];

        return response()->json($stats);
    }

    /**
     * Get permissions for a specific role.
     */
    public function getPermissions(Rol $rol)
    {
        $permisos = $rol->permisos;
        
        return response()->json([
            'permisos' => $permisos,
        ]);
    }

    /**
     * Get all permissions.
     */
    public function getAllPermisos()
    {
        $permisos = Permiso::orderBy('nombre')->get();
        
        return response()->json([
            'permisos' => $permisos,
        ]);
    }

    /**
     * Assign permissions to role.
     */
    public function assignPermissions(Request $request, Rol $rol)
    {
        $request->validate([
            'permisos' => 'required|array',
            'permisos.*' => 'exists:permisos,id',
        ]);

        $rol->permisos()->sync($request->permisos);

        return response()->json([
            'message' => 'Permisos asignados exitosamente.',
            'rol' => $rol->fresh()->load('permisos'),
        ]);
    }

    /**
     * Remove permissions from role.
     */
    public function removePermissions(Request $request, Rol $rol)
    {
        $request->validate([
            'permisos' => 'required|array',
            'permisos.*' => 'exists:permisos,id',
        ]);

        $rol->permisos()->detach($request->permisos);

        return response()->json([
            'message' => 'Permisos removidos exitosamente.',
            'rol' => $rol->fresh()->load('permisos'),
        ]);
    }
} 