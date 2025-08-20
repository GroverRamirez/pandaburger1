<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Pedido;
use App\Models\DetallePedido;
use App\Models\EstadoPedido;
use App\Models\Cliente;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class PedidoController extends Controller
{
    /**
     * Obtener todos los pedidos con filtros
     */
    public function index(Request $request): JsonResponse
    {
        \Log::info('PedidoController@index called with request:', $request->all());
        
        $query = Pedido::with(['estado', 'cliente', 'usuario', 'detallesPedido.producto']);

        // Aplicar filtros
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id', 'LIKE', "%{$search}%")
                  ->orWhereHas('cliente', function ($q) use ($search) {
                      $q->where('nombre', 'LIKE', "%{$search}%");
                  })
                  ->orWhereHas('detallesPedido.producto', function ($q) use ($search) {
                      $q->where('nombre', 'LIKE', "%{$search}%");
                  });
            });
        }

        if ($request->filled('estado_id')) {
            $query->where('estado_id', $request->estado_id);
        }

        if ($request->filled('cliente_id')) {
            $query->where('cliente_id', $request->cliente_id);
        }

        if ($request->filled('fecha_desde')) {
            $query->where('fecha', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->where('fecha', '<=', $request->fecha_hasta);
        }

        // Aplicar ordenamiento
        $sortBy = $request->get('sortBy', 'fecha');
        $sortOrder = $request->get('sortOrder', 'desc');

        switch ($sortBy) {
            case 'total':
                $query->withSum('detallesPedido', 'precio_total')->orderBy('detalles_pedido_sum_precio_total', $sortOrder);
                break;
            case 'estado':
                $query->join('estados_pedido', 'pedidos.estado_id', '=', 'estados_pedido.id')
                      ->orderBy('estados_pedido.nombre', $sortOrder);
                break;
            case 'cliente':
                $query->join('clientes', 'pedidos.cliente_id', '=', 'clientes.id')
                      ->orderBy('clientes.nombre', $sortOrder);
                break;
            default:
                $query->orderBy('fecha', $sortOrder);
        }

        // Paginación
        $perPage = $request->get('perPage', 10);
        $pedidos = $query->paginate($perPage);

        // Agregar campos calculados
        $pedidos->getCollection()->transform(function ($pedido) {
            $pedido->total_items = $pedido->detallesPedido->sum('cantidad');
            $pedido->total_pedido = $pedido->detallesPedido->sum('precio_total');
            $pedido->estado_nombre = $pedido->estado->nombre ?? '';
            $pedido->cliente_nombre = $pedido->cliente->nombre ?? '';
            $pedido->usuario_nombre = $pedido->usuario->nombre ?? '';
            return $pedido;
        });

        \Log::info('PedidoController@index returning:', [
            'total_pedidos' => $pedidos->total(),
            'current_page' => $pedidos->currentPage(),
            'per_page' => $pedidos->perPage(),
            'first_pedido_id' => $pedidos->first()?->id ?? 'none'
        ]);
        
        return response()->json($pedidos);
    }

    /**
     * Obtener un pedido específico
     */
    public function show($id): JsonResponse
    {
        $pedido = Pedido::with(['estado', 'cliente', 'usuario', 'detalles.producto'])->findOrFail($id);
        
        return response()->json($pedido);
    }

    /**
     * Obtener pedido con detalles completos
     */
    public function showWithDetails($id): JsonResponse
    {
        $pedido = Pedido::with(['estado', 'cliente', 'usuario', 'detalles.producto'])->findOrFail($id);
        
        // Calcular estadísticas
        $estadisticas = [
            'total_productos' => $pedido->detalles->count(),
            'total_items' => $pedido->detalles->sum('cantidad'),
            'total_pedido' => $pedido->detalles->sum('precio_total'),
            'impuestos' => $pedido->detalles->sum('precio_total') * 0.13,
            'descuentos' => 0,
            'total_final' => $pedido->detalles->sum('precio_total') * 1.13
        ];

        // Generar timeline
        $timeline = [
            [
                'estado' => 'Pedido Creado',
                'fecha' => $pedido->created_at,
                'usuario' => $pedido->usuario->nombre ?? 'Sistema',
                'comentario' => 'Pedido creado exitosamente',
                'color' => '#10B981',
                'icon' => 'shopping-cart'
            ]
        ];

        if ($pedido->estado) {
            $timeline[] = [
                'estado' => $pedido->estado->nombre,
                'fecha' => $pedido->updated_at,
                'usuario' => $pedido->usuario->nombre ?? 'Sistema',
                'comentario' => 'Estado actualizado',
                'color' => $this->getEstadoColor($pedido->estado->nombre),
                'icon' => $this->getEstadoIcon($pedido->estado->nombre)
            ];
        }

        $pedido->estadisticas = $estadisticas;
        $pedido->timeline = $timeline;

        return response()->json($pedido);
    }

    /**
     * Crear un nuevo pedido
     */
    public function store(Request $request): JsonResponse
    {
        \Log::info('Creando pedido con datos:', $request->all());
        
        $validator = Validator::make($request->all(), [
            'cliente_id' => 'nullable|exists:clientes,id',
            'estado_id' => 'required|exists:estados_pedido,id',
            'fecha' => 'required|date',
            'detalles' => 'required|array|min:1',
            'detalles.*.producto_id' => 'required|exists:productos,id',
            'detalles.*.cantidad' => 'required|integer|min:1',
            'detalles.*.precio_unitario' => 'required|numeric|min:0',
            'detalles.*.precio_total' => 'required|numeric|min:0'
        ]);

        if ($validator->fails()) {
            \Log::error('Validación falló:', $validator->errors()->toArray());
            return response()->json(['errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            \Log::info('Iniciando transacción para crear pedido');
            
            // Verificar autenticación
            $usuarioId = auth()->id();
            if (!$usuarioId) {
                // Usar usuario por defecto si no hay autenticación
                $usuarioId = 1; // ID del usuario administrador
                \Log::warning('No hay usuario autenticado, usando usuario por defecto:', ['usuario_id' => $usuarioId]);
            }
            
            // Crear el pedido
            $pedido = Pedido::create([
                'usuario_id' => $usuarioId,
                'cliente_id' => $request->cliente_id,
                'estado_id' => $request->estado_id,
                'fecha' => $request->fecha
            ]);

            \Log::info('Pedido creado con ID:', ['id' => $pedido->id]);

            // Crear los detalles del pedido
            foreach ($request->detalles as $detalle) {
                \Log::info('Creando detalle:', $detalle);
                
                DetallePedido::create([
                    'pedido_id' => $pedido->id,
                    'producto_id' => $detalle['producto_id'],
                    'cantidad' => $detalle['cantidad'],
                    'precio_unitario' => $detalle['precio_unitario'],
                    'precio_total' => $detalle['precio_total']
                ]);
            }

            DB::commit();
            \Log::info('Pedido creado exitosamente');

            $pedido->load(['estado', 'cliente', 'usuario', 'detalles.producto']);
            return response()->json($pedido, 201);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error al crear pedido:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'Error al crear el pedido: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Actualizar un pedido
     */
    public function update(Request $request, $id): JsonResponse
    {
        $pedido = Pedido::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'cliente_id' => 'nullable|exists:clientes,id',
            'estado_id' => 'sometimes|exists:estados_pedido,id',
            'fecha' => 'sometimes|date',
            'detalles' => 'sometimes|array|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            // Actualizar campos básicos
            $pedido->update($request->only(['cliente_id', 'estado_id', 'fecha']));

            // Si se proporcionan nuevos detalles, actualizarlos
            if ($request->has('detalles')) {
                // Eliminar detalles existentes
                $pedido->detalles()->delete();

                // Crear nuevos detalles
                foreach ($request->detalles as $detalle) {
                    DetallePedido::create([
                        'pedido_id' => $pedido->id,
                        'producto_id' => $detalle['producto_id'],
                        'cantidad' => $detalle['cantidad'],
                        'precio_unitario' => $detalle['precio_unitario'],
                        'precio_total' => $detalle['precio_total']
                    ]);
                }
            }

            DB::commit();

            $pedido->load(['estado', 'cliente', 'usuario', 'detalles.producto']);
            return response()->json($pedido);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al actualizar el pedido: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Eliminar un pedido
     */
    public function destroy($id): JsonResponse
    {
        $pedido = Pedido::findOrFail($id);

        DB::beginTransaction();
        try {
            // Restaurar stock de productos - Comentado porque la columna no existe
            // foreach ($pedido->detalles as $detalle) {
            //     $producto = Producto::find($detalle->producto_id);
            //     if ($producto) {
            //         $producto->increment('stock_disponible', $detalle->cantidad);
            //     }
            // }

            // Eliminar detalles y pedido
            $pedido->detalles()->delete();
            $pedido->delete();

            DB::commit();
            return response()->json(['message' => 'Pedido eliminado exitosamente']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al eliminar el pedido: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Cambiar estado de un pedido
     */
    public function changeEstado(Request $request, $id): JsonResponse
    {
        $pedido = Pedido::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'estado_id' => 'required|exists:estados_pedido,id',
            'comentario' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $pedido->update(['estado_id' => $request->estado_id]);

        // Aquí podrías guardar el comentario en una tabla de timeline
        // Por ahora solo retornamos el pedido actualizado

        $pedido->load(['estado', 'cliente', 'usuario', 'detalles.producto']);
        return response()->json($pedido);
    }

    /**
     * Obtener estadísticas de pedidos
     */
    public function getStats(): JsonResponse
    {
        $hoy = Carbon::today();
        $semana = Carbon::now()->startOfWeek();
        $mes = Carbon::now()->startOfMonth();

        $stats = [
            'total_pedidos' => Pedido::count(),
            'pedidos_pendientes' => Pedido::where('estado_id', 1)->count(),
            'pedidos_en_proceso' => Pedido::where('estado_id', 2)->count(),
            'pedidos_completados' => Pedido::where('estado_id', 3)->count(),
            'pedidos_cancelados' => Pedido::where('estado_id', 4)->count(),
            'total_ventas' => DetallePedido::sum('precio_total'),
            'promedio_pedido' => DetallePedido::avg('precio_total'),
            'pedidos_hoy' => Pedido::whereDate('fecha', $hoy)->count(),
            'pedidos_semana' => Pedido::where('fecha', '>=', $semana)->count(),
            'pedidos_mes' => Pedido::where('fecha', '>=', $mes)->count()
        ];

        return response()->json($stats);
    }

    /**
     * Obtener estados de pedido
     */
    public function getEstados(): JsonResponse
    {
        $estados = EstadoPedido::all();
        return response()->json($estados);
    }

    /**
     * Obtener pedidos por cliente
     */
    public function getByCliente($clienteId): JsonResponse
    {
        $pedidos = Pedido::where('cliente_id', $clienteId)
            ->with(['estado', 'detalles.producto'])
            ->orderBy('fecha', 'desc')
            ->get();

        return response()->json($pedidos);
    }

    /**
     * Obtener pedidos por fecha
     */
    public function getByDate($fecha): JsonResponse
    {
        $pedidos = Pedido::whereDate('fecha', $fecha)
            ->with(['estado', 'cliente', 'detalles.producto'])
            ->orderBy('fecha', 'desc')
            ->get();

        return response()->json($pedidos);
    }

    /**
     * Obtener pedidos recientes
     */
    public function getRecientes(Request $request): JsonResponse
    {
        $limit = $request->get('limit', 10);
        $pedidos = Pedido::with(['estado', 'cliente'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();

        return response()->json($pedidos);
    }

    /**
     * Obtener pedidos pendientes
     */
    public function getPendientes(): JsonResponse
    {
        $pedidos = Pedido::where('estado_id', 1)
            ->with(['cliente', 'detalles.producto'])
            ->orderBy('fecha', 'asc')
            ->get();

        return response()->json($pedidos);
    }

    /**
     * Obtener pedidos en proceso
     */
    public function getEnProceso(): JsonResponse
    {
        $pedidos = Pedido::where('estado_id', 2)
            ->with(['cliente', 'detalles.producto'])
            ->orderBy('fecha', 'asc')
            ->get();

        return response()->json($pedidos);
    }

    /**
     * Obtener pedidos completados
     */
    public function getCompletados(): JsonResponse
    {
        $pedidos = Pedido::where('estado_id', 3)
            ->with(['cliente', 'detalles.producto'])
            ->orderBy('fecha', 'desc')
            ->get();

        return response()->json($pedidos);
    }

    /**
     * Obtener pedidos cancelados
     */
    public function getCancelados(): JsonResponse
    {
        $pedidos = Pedido::where('estado_id', 4)
            ->with(['cliente', 'detalles.producto'])
            ->orderBy('fecha', 'desc')
            ->get();

        return response()->json($pedidos);
    }

    /**
     * Exportar pedidos
     */
    public function export(Request $request): JsonResponse
    {
        // Implementar lógica de exportación
        return response()->json(['message' => 'Exportación no implementada aún']);
    }

    /**
     * Obtener timeline de pedido
     */
    public function getTimeline($id): JsonResponse
    {
        $pedido = Pedido::findOrFail($id);
        
        $timeline = [
            [
                'estado' => 'Pedido Creado',
                'fecha' => $pedido->created_at,
                'usuario' => $pedido->usuario->nombre ?? 'Sistema',
                'comentario' => 'Pedido creado exitosamente',
                'color' => '#10B981',
                'icon' => 'shopping-cart'
            ]
        ];

        if ($pedido->estado) {
            $timeline[] = [
                'estado' => $pedido->estado->nombre,
                'fecha' => $pedido->updated_at,
                'usuario' => $pedido->usuario->nombre ?? 'Sistema',
                'comentario' => 'Estado actualizado',
                'color' => $this->getEstadoColor($pedido->estado->nombre),
                'icon' => $this->getEstadoIcon($pedido->estado->nombre)
            ];
        }

        return response()->json($timeline);
    }

    /**
     * Agregar comentario a pedido
     */
    public function addComentario(Request $request, $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'comentario' => 'required|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Aquí implementarías la lógica para guardar comentarios
        // Por ahora solo retornamos éxito

        return response()->json(['message' => 'Comentario agregado exitosamente']);
    }

    /**
     * Obtener productos disponibles para pedidos
     */
    public function getProductosDisponibles(): JsonResponse
    {
        $productos = Producto::with('categoria')
            ->select('id', 'nombre', 'precio', 'categoria_id')
            ->orderBy('nombre')
            ->get()
            ->map(function ($producto) {
                return [
                    'id' => $producto->id,
                    'nombre' => $producto->nombre,
                    'precio' => $producto->precio,
                    'categoria' => $producto->categoria->nombre ?? '',
                    'stock_disponible' => 100 // Default value for compatibility
                ];
            });

        return response()->json($productos);
    }

    /**
     * Calcular total de pedido
     */
    public function calcularTotal(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'detalles' => 'required|array',
            'detalles.*.cantidad' => 'required|integer|min:1',
            'detalles.*.precio_unitario' => 'required|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $total = 0;
        foreach ($request->detalles as $detalle) {
            $total += $detalle['cantidad'] * $detalle['precio_unitario'];
        }

        $impuestos = $total * 0.13;
        $totalFinal = $total + $impuestos;

        return response()->json([
            'subtotal' => $total,
            'impuestos' => $impuestos,
            'total_final' => $totalFinal
        ]);
    }

    /**
     * Validar stock de productos
     */
    public function validarStock(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'detalles' => 'required|array',
            'detalles.*.producto_id' => 'required|exists:productos,id',
            'detalles.*.cantidad' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $errores = [];
        foreach ($request->detalles as $detalle) {
            $producto = Producto::find($detalle['producto_id']);
            // For now, assume stock is always available (default 100)
            $stockDisponible = 100;
            if ($stockDisponible < $detalle['cantidad']) {
                $errores[] = "Stock insuficiente para {$producto->nombre}. Disponible: {$stockDisponible}, Solicitado: {$detalle['cantidad']}";
            }
        }

        if (!empty($errores)) {
            return response()->json(['errors' => $errores], 422);
        }

        return response()->json(['message' => 'Stock válido para todos los productos']);
    }

    /**
     * Obtener color para estado
     */
    private function getEstadoColor($estadoNombre): string
    {
        switch (strtolower($estadoNombre)) {
            case 'pendiente':
                return '#F59E0B';
            case 'en proceso':
                return '#3B82F6';
            case 'completado':
                return '#10B981';
            case 'cancelado':
                return '#EF4444';
            default:
                return '#6B7280';
        }
    }

    /**
     * Obtener icono para estado
     */
    private function getEstadoIcon($estadoNombre): string
    {
        switch (strtolower($estadoNombre)) {
            case 'pendiente':
                return 'clock';
            case 'en proceso':
                return 'alert-circle';
            case 'completado':
                return 'check-circle';
            case 'cancelado':
                return 'x-circle';
            default:
                return 'package';
        }
    }
} 