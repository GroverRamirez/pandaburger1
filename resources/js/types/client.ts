export interface Cliente {
  id: number;
  nombre: string;
  apellido?: string | null;
  correo_electronico: string | null;
  telefono: string | null;
  direccion: string | null;
  ci?: string | null;
  fecha_nacimiento?: string | null;
  last_login_at: string | null;
  created_at: string;
  updated_at: string;
  deleted_at: string | null;
}

export interface ClienteFormData {
  nombre: string;
  apellido?: string;
  correo_electronico: string;
  telefono?: string;
  direccion?: string;
  ci?: string;
  fecha_nacimiento?: string;
  password_hash?: string;
}

export interface ClienteFilters {
  search?: string;
  sortBy?: 'nombre' | 'correo_electronico' | 'created_at' | 'last_login_at';
  sortOrder?: 'asc' | 'desc';
  page?: number;
  perPage?: number;
  // Filtros avanzados
  status?: 'all' | 'active' | 'inactive' | 'new' | 'vip';
  dateRange?: 'all' | 'today' | 'week' | 'month' | 'year';
  minOrders?: number;
  maxOrders?: number;
  minSpent?: number;
  maxSpent?: number;
}

export interface ClienteOrder {
  id: number;
  estado: {
    id: number;
    nombre: string;
  };
  total: number;
  created_at: string;
  updated_at: string;
}

export interface ClienteStats {
  total: number;
  active: number;
  newThisMonth: number;
  totalOrders: number;
  averageOrderValue: number;
  growthRate: number;
  topSpenders: number;
}

export interface ClienteWithOrders extends Cliente {
  pedidos: ClienteOrder[];
}
