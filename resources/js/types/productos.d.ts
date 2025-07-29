export interface Producto {
    id: number;
    nombre: string;
    descripcion?: string;
    precio: number;
    categoria_id: number;
    imagen_url?: string;
    created_at: string;
    updated_at: string;
    deleted_at?: string;
    categoria?: Categoria;
}

export interface Categoria {
    id: number;
    nombre: string;
    created_at: string;
    updated_at: string;
    deleted_at?: string;
    productos?: Producto[];
}

export interface ProductoFormData {
    nombre: string;
    descripcion?: string;
    precio: number;
    categoria_id: number;
    imagen_url?: string;
    imagen?: File;
}

export interface ProductosFilters {
    search?: string;
    categoria_id?: number;
    precio_min?: number;
    precio_max?: number;
    sort_by?: 'nombre' | 'precio' | 'created_at';
    sort_order?: 'asc' | 'desc';
}

export interface ProductosResponse {
    data: Producto[];
    links: {
        first: string;
        last: string;
        prev?: string;
        next?: string;
    };
    meta: {
        current_page: number;
        from: number;
        last_page: number;
        per_page: number;
        to: number;
        total: number;
    };
} 