<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::with('rol')
            ->withCount('pedidos')
            ->orderBy('created_at', 'desc');

        // Aplicar filtros
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('correo_electronico', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('rol_id', $request->role);
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->whereNull('deleted_at');
            } elseif ($request->status === 'inactive') {
                $query->whereNotNull('deleted_at');
            }
        }

        $users = $query->paginate(10);

        $roles = Rol::orderBy('nombre')->get();

        // Calcular estadísticas de usuarios
        $userStats = [
            'total_users' => User::count(),
            'active_users' => User::whereNull('deleted_at')->count(),
            'inactive_users' => User::whereNotNull('deleted_at')->count(),
            'new_this_month' => User::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
        ];

        return Inertia::render('Usuarios/Index', [
            'users' => $users->items(),
            'pagination' => [
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'per_page' => $users->perPage(),
                'total' => $users->total(),
                'from' => $users->firstItem(),
                'to' => $users->lastItem(),
            ],
            'roles' => $roles,
            'filters' => $request->only(['search', 'role', 'status']),
            'userStats' => $userStats,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Rol::all();
        
        return Inertia::render('Usuarios/Create', [
            'roles' => $roles,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:60',
            'correo_electronico' => 'required|email|max:120|unique:users,correo_electronico',
            'telefono' => 'nullable|string|max:30',
            'rol_id' => 'required|exists:roles,id',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'nombre' => $request->nombre,
            'correo_electronico' => $request->correo_electronico,
            'telefono' => $request->telefono,
            'rol_id' => $request->rol_id,
            'password_hash' => Hash::make($request->password),
        ]);

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $usuario)
    {
        $usuario->load(['rol', 'pedidos' => function ($query) {
            $query->latest()->take(5);
        }]);

        return Inertia::render('Usuarios/Show', [
            'user' => $usuario,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $usuario)
    {
        $roles = Rol::all();
        
        return Inertia::render('Usuarios/Edit', [
            'user' => $usuario->load('rol'),
            'roles' => $roles,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $usuario)
    {
        $request->validate([
            'nombre' => 'required|string|max:60',
            'correo_electronico' => [
                'required',
                'email',
                'max:120',
                Rule::unique('users', 'correo_electronico')->ignore($usuario->id),
            ],
            'telefono' => 'nullable|string|max:30',
            'rol_id' => 'required|exists:roles,id',
        ]);

        $usuario->update([
            'nombre' => $request->nombre,
            'correo_electronico' => $request->correo_electronico,
            'telefono' => $request->telefono,
            'rol_id' => $request->rol_id,
        ]);

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $usuario)
    {
        // Soft delete
        $usuario->delete();

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario eliminado exitosamente.');
    }

    /**
     * Restore a soft deleted user.
     */
    public function restore($id)
    {
        $usuario = User::withTrashed()->findOrFail($id);
        $usuario->restore();

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario restaurado exitosamente.');
    }

    /**
     * Get user profile.
     */
    public function profile()
    {
        $user = auth()->user()->load('rol');
        
        return Inertia::render('Usuarios/Profile', [
            'user' => $user,
        ]);
    }

    /**
     * Update user profile.
     */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'nombre' => 'required|string|max:60',
            'correo_electronico' => [
                'required',
                'email',
                'max:120',
                Rule::unique('users', 'correo_electronico')->ignore($user->id),
            ],
            'telefono' => 'nullable|string|max:30',
        ]);

        $user->update([
            'nombre' => $request->nombre,
            'correo_electronico' => $request->correo_electronico,
            'telefono' => $request->telefono,
        ]);

        return response()->json([
            'message' => 'Perfil actualizado exitosamente.',
            'user' => $user->fresh()->load('rol'),
        ]);
    }

    /**
     * Update user password.
     */
    public function updatePassword(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, $user->password_hash)) {
            return response()->json([
                'message' => 'La contraseña actual es incorrecta.',
            ], 422);
        }

        $user->update([
            'password_hash' => Hash::make($request->new_password),
        ]);

        return response()->json([
            'message' => 'Contraseña actualizada exitosamente.',
        ]);
    }

    /**
     * Get users statistics.
     */
    public function statistics()
    {
        $stats = [
            'total_users' => User::count(),
            'active_users' => User::whereNull('deleted_at')->count(),
            'inactive_users' => User::whereNotNull('deleted_at')->count(),
            'users_by_role' => User::with('rol')
                ->selectRaw('rol_id, count(*) as count')
                ->groupBy('rol_id')
                ->get()
                ->mapWithKeys(function ($item) {
                    return [$item->rol->nombre => $item->count];
                }),
        ];

        return response()->json($stats);
    }
} 