export interface EstadoPedido {
  id: number;
  nombre: string;
  color?: string;
  icon?: string;
}

export interface ProductoPedido {
  id: number;
  nombre: string;
  precio: number;
  imagen_url?: string;
  categoria?: string;
  stock_disponible: number;
}

export interface DetallePedido {
  id: number;
  pedido_id: number;
  producto_id: number;
  cantidad: number;
  precio_unitario: number;
  precio_total: number;
  producto?: ProductoPedido;
  created_at: string;
  updated_at: string;
}

export interface Pedido {
  id: number;
  usuario_id?: number;
  cliente_id?: number;
  estado_id: number;
  fecha: string;
  created_at: string;
  updated_at: string;
  deleted_at?: string;
  
  // Relaciones
  estado?: EstadoPedido;
  cliente?: {
    id: number;
    nombre: string;
    correo_electronico?: string;
    telefono?: string;
  };
  usuario?: {
    id: number;
    nombre: string;
    email: string;
  };
  detalles?: DetallePedido[];
  
  // Campos calculados
  total_items?: number;
  total_pedido?: number;
  estado_nombre?: string;
  cliente_nombre?: string;
  usuario_nombre?: string;
}

export interface PedidoFormData {
  cliente_id?: number;
  estado_id: number;
  fecha: string;
  detalles: DetallePedidoForm[];
}

export interface DetallePedidoForm {
  producto_id: number;
  cantidad: number;
  precio_unitario: number;
  precio_total: number;
}

export interface PedidoFilters {
  search?: string;
  estado_id?: number;
  cliente_id?: number;
  fecha_desde?: string;
  fecha_hasta?: string;
  sortBy?: 'fecha' | 'total' | 'estado' | 'cliente';
  sortOrder?: 'asc' | 'desc';
  page?: number;
  perPage?: number;
}

export interface PedidoStats {
  total_pedidos: number;
  pedidos_pendientes: number;
  pedidos_en_proceso: number;
  pedidos_completados: number;
  pedidos_cancelados: number;
  total_ventas: number;
  promedio_pedido: number;
  pedidos_hoy: number;
  pedidos_semana: number;
  pedidos_mes: number;
}

export interface PaginatedResponse<T> {
  data: T[];
  meta: {
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
  };
}

export interface PedidoTimeline {
  estado: string;
  fecha: string;
  usuario?: string;
  comentario?: string;
  color: string;
  icon: string;
}

export interface PedidoWithDetails extends Pedido {
  detalles: DetallePedido[];
  timeline: PedidoTimeline[];
  estadisticas: {
    total_productos: number;
    total_items: number;
    total_pedido: number;
    impuestos: number;
    descuentos: number;
    total_final: number;
  };
}
