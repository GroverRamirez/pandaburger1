import type { Cliente, ClienteFormData, ClienteFilters, ClienteOrder } from '@/types/client';
import { http } from '@/lib/axios';

export const clientService = {
  async getClients(filters: ClienteFilters = {}) {
    const params = new URLSearchParams();
    
    if (filters.search) params.append('search', filters.search);
    if (filters.sortBy) params.append('sort_by', filters.sortBy);
    if (filters.sortOrder) params.append('sort_order', filters.sortOrder);
    if (filters.page) params.append('page', filters.page.toString());
    if (filters.perPage) params.append('per_page', filters.perPage.toString());
    
    try {
      const response = await http.get<{ data: Cliente[]; meta: any }>('/api/v1/clientes', { params });
      return response.data;
    } catch (error) {
      console.error('Error fetching clients:', error);
      throw error;
    }
  },

  async getClient(id: number): Promise<Cliente> {
    try {
      const response = await http.get<{ data: Cliente }>(`/api/v1/clientes/${id}`);
      return response.data.data;
    } catch (error) {
      console.error(`Error fetching client with id ${id}:`, error);
      throw error;
    }
  },

  async createClient(data: ClienteFormData): Promise<Cliente> {
    try {
      const response = await http.post<{ data: Cliente }>('/api/v1/clientes', data);
      return response.data.data;
    } catch (error) {
      console.error('Error creating client:', error);
      throw error;
    }
  },

  async updateClient(id: number, data: Partial<ClienteFormData>): Promise<Cliente> {
    try {
      const response = await http.put<{ data: Cliente }>(`/api/v1/clientes/${id}`, data);
      return response.data.data;
    } catch (error) {
      console.error(`Error updating client with id ${id}:`, error);
      throw error;
    }
  },

  async deleteClient(id: number): Promise<void> {
    try {
      await http.delete(`/api/v1/clientes/${id}`);
    } catch (error) {
      console.error(`Error deleting client with id ${id}:`, error);
      throw error;
    }
  },

  async getClientOrders(id: number): Promise<ClienteOrder[]> {
    try {
      const response = await http.get<{ data: ClienteOrder[] }>(`/api/v1/clientes/${id}/orders`);
      return response.data.data;
    } catch (error) {
      console.error(`Error fetching orders for client ${id}:`, error);
      throw error;
    }
  }
};
