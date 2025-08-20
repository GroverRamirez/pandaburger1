import { http } from '@/lib/axios';
import type { 
  Pedido, 
  PedidoFormData, 
  PedidoFilters, 
  PedidoStats, 
  PedidoWithDetails,
  EstadoPedido,
  PaginatedResponse,
  ProductoPedido
} from '@/types/pedidos';

export const pedidoService = {
  // Obtener todos los pedidos con filtros
  async getPedidos(filters: PedidoFilters = {}): Promise<PaginatedResponse<Pedido>> {
    const response = await http.get('/api/v1/pedidos', { params: filters });
    return response.data as PaginatedResponse<Pedido>;
  },

  // Obtener un pedido específico
  async getPedido(id: number) {
    const response = await http.get(`/api/v1/pedidos/${id}`);
    return response.data;
  },

  // Obtener pedido con detalles completos
  async getPedidoWithDetails(id: number): Promise<PedidoWithDetails> {
    const response = await http.get(`/api/v1/pedidos/${id}/details`);
    return response.data as PedidoWithDetails;
  },

  // Crear un nuevo pedido
  async createPedido(pedidoData: PedidoFormData) {
    const response = await http.post('/api/v1/pedidos', pedidoData);
    return response.data;
  },

  // Actualizar un pedido
  async updatePedido(id: number, pedidoData: Partial<PedidoFormData>) {
    const response = await http.put(`/api/v1/pedidos/${id}`, pedidoData);
    return response.data;
  },

  // Eliminar un pedido
  async deletePedido(id: number) {
    const response = await http.delete(`/api/v1/pedidos/${id}`);
    return response.data;
  },

  // Cambiar estado de un pedido
  async changeEstado(id: number, estadoId: number, comentario?: string) {
    const response = await http.patch(`/api/v1/pedidos/${id}/estado`, {
      estado_id: estadoId,
      comentario
    });
    return response.data;
  },

  // Obtener estadísticas de pedidos
  async getPedidoStats(): Promise<PedidoStats> {
    const response = await http.get('/api/v1/pedidos/stats');
    return response.data as PedidoStats;
  },

  // Obtener estados de pedido
  async getEstadosPedido(): Promise<EstadoPedido[]> {
    const response = await http.get('/api/v1/pedidos/estados');
    return response.data as PedidoStats;
  },

  // Obtener pedidos por cliente
  async getPedidosByCliente(clienteId: number) {
    const response = await http.get(`/api/v1/pedidos/cliente/${clienteId}`);
    return response.data;
  },

  // Obtener pedidos por fecha
  async getPedidosByDate(fecha: string) {
    const response = await http.get(`/api/v1/pedidos/fecha/${fecha}`);
    return response.data;
  },

  // Obtener pedidos recientes
  async getPedidosRecientes(limit: number = 10) {
    const response = await http.get('/api/v1/pedidos/recientes', { params: { limit } });
    return response.data;
  },

  // Obtener pedidos pendientes
  async getPedidosPendientes() {
    const response = await http.get('/api/v1/pedidos/pendientes');
    return response.data;
  },

  // Obtener pedidos en proceso
  async getPedidosEnProceso() {
    const response = await http.get('/api/v1/pedidos/en-proceso');
    return response.data;
  },

  // Obtener pedidos completados
  async getPedidosCompletados() {
    const response = await http.get('/api/v1/pedidos/completados');
    return response.data;
  },

  // Obtener pedidos cancelados
  async getPedidosCancelados() {
    const response = await http.get('/api/v1/pedidos/cancelados');
    return response.data;
  },

  // Exportar pedidos
  async exportPedidos(filters: PedidoFilters = {}, format: 'pdf' | 'excel' = 'pdf') {
    const response = await http.get('/api/v1/pedidos/export', { 
      params: { ...filters, format },
      responseType: 'blob'
    });
    return response.data;
  },

  // Obtener timeline de un pedido
  async getPedidoTimeline(id: number) {
    const response = await http.get(`/api/v1/pedidos/${id}/timeline`);
    return response.data;
  },

  // Agregar comentario a un pedido
  async addComentario(id: number, comentario: string) {
    const response = await http.post(`/api/v1/pedidos/${id}/comentarios`, { comentario });
    return response.data;
  },

  // Obtener productos disponibles para pedidos
  async getProductosDisponibles(): Promise<ProductoPedido[]> {
    const response = await http.get('/api/v1/pedidos/productos/disponibles');
    return response.data as ProductoPedido[];
  },

  // Calcular total de un pedido
  async calcularTotal(detalles: any[]) {
    const response = await http.post('/api/v1/pedidos/calcular-total', { detalles });
    return response.data;
  },

  // Validar stock de productos
  async validarStock(detalles: any[]) {
    const response = await http.post('/api/v1/pedidos/validar-stock', { detalles });
    return response.data;
  }
};
