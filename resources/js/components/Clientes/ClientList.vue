<script setup lang="ts">
import { ref, onMounted } from 'vue';
import axios from 'axios';
import type { Cliente, ClienteFilters } from '@/types/client';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Plus, Search, ArrowUpDown, Eye, Edit, Trash2 } from 'lucide-vue-next';
import { router } from '@inertiajs/vue3';

interface ApiResponse<T> {
  data: T[];
  meta: {
    total: number;
    per_page: number;
    current_page: number;
    last_page: number;
  };
}

// Refs for component state
const clients = ref<Cliente[]>([]);
const loading = ref(true);
const error = ref<string | null>(null);
const filters = ref<ClienteFilters>({
  search: '',
  sortBy: 'nombre',
  sortOrder: 'asc',
  page: 1,
  perPage: 10,
});

const meta = ref({
  total: 0,
  lastPage: 1,
  currentPage: 1,
  perPage: 10,
});

// Methods
const goTo = (routeName: string, params: Record<string, any> = {}) => {
  router.visit(route(routeName, params));
};

const goBack = () => {
  window.history.back();
};

const formatDate = (dateString: string | number | Date | null | undefined): string => {
  if (!dateString) return 'Nunca';
  return new Date(dateString).toLocaleDateString('es-BO');
};

const fetchClients = async () => {
  try {
    loading.value = true;
    error.value = null;
    
    // Convert filters to query parameters
    const params = new URLSearchParams();
    if (filters.value.search) params.append('search', filters.value.search);
    if (filters.value.sortBy) params.append('sort_by', filters.value.sortBy);
    if (filters.value.sortOrder) params.append('sort_order', filters.value.sortOrder);
    if (filters.value.page) params.append('page', filters.value.page.toString());
    if (filters.value.perPage) params.append('per_page', filters.value.perPage.toString());
    
    console.log('Fetching clients with params:', params.toString()); // Debug log
    
    const response = await axios.get(`/api/clientes?${params.toString()}`);
    console.log('API Response:', response.data); // Debug log
    
    // Handle the paginated response from Laravel
    if (response.data && response.data.data) {
      clients.value = response.data.data;
      meta.value = {
        total: response.data.meta?.total || 0,
        lastPage: response.data.meta?.last_page || 1,
        currentPage: response.data.meta?.current_page || 1,
        perPage: response.data.meta?.per_page || 10,
      };
    } else {
      // Fallback for unexpected response format
      console.warn('Unexpected API response format:', response.data);
      clients.value = [];
      meta.value = {
        total: 0,
        lastPage: 1,
        currentPage: 1,
        perPage: 10,
      };
    }
  } catch (err: any) {
    error.value = 'Error al cargar la lista de clientes';
    console.error('Error fetching clients:', err);
    
    if (err.response?.data?.message) {
      error.value = err.response.data.message;
    }
  } finally {
    loading.value = false;
  }
};

const changePage = (page: number) => {
  if (page >= 1 && page <= meta.value.lastPage) {
    filters.value.page = page;
    fetchClients();
  }
};

const changePerPage = (perPage: number) => {
  filters.value.perPage = perPage;
  filters.value.page = 1; // Reset to first page
  fetchClients();
};

const handleSearch = () => {
  filters.value.page = 1;
  fetchClients();
};

const sort = (field: ClienteFilters['sortBy']) => {
  if (filters.value.sortBy === field) {
    filters.value.sortOrder = filters.value.sortOrder === 'asc' ? 'desc' : 'asc';
  } else {
    filters.value.sortBy = field;
    filters.value.sortOrder = 'asc';
  }
  fetchClients();
};

const viewClient = (id: number) => {
  goTo('clientes.show', { cliente: id });
};

const createClient = () => {
  goTo('clientes.create');
};

const editClient = (id: number) => {
  goTo('clientes.edit', { cliente: id });
};

const deleteClient = async (id: number) => {
  if (confirm('¿Está seguro de que desea eliminar este cliente?')) {
    try {
      await axios.delete(`/api/clientes/${id}`);
      await fetchClients();
      // Show success message
      if (window.toastr) {
        window.toastr.success('Cliente eliminado correctamente');
      } else {
        alert('Cliente eliminado correctamente');
      }
    } catch (err) {
      console.error('Error deleting client:', err);
      if (window.toastr) {
        window.toastr.error('Error al eliminar el cliente');
      } else {
        alert('Error al eliminar el cliente');
      }
    }
  }
};

// Fetch clients when component is mounted
onMounted(() => {
  fetchClients();
});

// Expose template variables
defineExpose({
  clients,
  loading,
  error,
  filters,
  meta,
  fetchClients,
  changePage,
  changePerPage,
  handleSearch,
  sort,
  viewClient,
  createClient,
  editClient,
  deleteClient,
  formatDate,
  goTo,
  goBack
});
</script>

<template>
  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-bold">Clientes</h1>
        <p class="text-muted-foreground">Gestión de clientes de Panda Burger</p>
      </div>
      <Button @click="goTo('clientes.create')">
        <Plus class="w-4 h-4 mr-2" />
        Nuevo Cliente
      </Button>
    </div>

    <Card>
      <CardHeader>
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
          <div class="w-full md:w-1/3">
            <div class="relative">
              <Search class="absolute left-2.5 top-2.5 h-4 w-4 text-muted-foreground" />
              <Input
                type="search"
                placeholder="Buscar clientes..."
                class="pl-8 w-full"
                :model-value="filters.search"
                @update:model-value="(value: string | number) => filters.search = String(value)"
                @keyup.enter="handleSearch"
              />
            </div>
          </div>
        </div>
      </CardHeader>
      <CardContent>
        <div v-if="loading" class="flex justify-center p-8">
          <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div>
        </div>
        <div v-else-if="error" class="text-center p-8 text-destructive">
          {{ error }}
        </div>
        <div v-else-if="clients.length === 0" class="text-center p-8 text-muted-foreground">
          No se encontraron clientes
        </div>
        <div v-else class="rounded-md border">
          <Table>
            <TableHeader>
              <TableRow>
                <TableHead class="w-[200px] cursor-pointer" @click="sort('nombre')">
                  <div class="flex items-center">
                    Nombre
                    <ArrowUpDown class="ml-2 h-4 w-4" />
                  </div>
                </TableHead>
                <TableHead>Correo Electrónico</TableHead>
                <TableHead>Teléfono</TableHead>
                <TableHead class="text-right">Último Acceso</TableHead>
                <TableHead class="w-[100px]">Acciones</TableHead>
              </TableRow>
            </TableHeader>
            <TableBody>
              <TableRow v-for="client in clients" :key="client.id">
                <TableCell class="font-medium">{{ client.nombre }}</TableCell>
                <TableCell>{{ client.correo_electronico }}</TableCell>
                <TableCell>{{ client.telefono || 'No especificado' }}</TableCell>
                <TableCell class="text-right">
                  {{ formatDate(client.last_login_at) }}
                </TableCell>
                <TableCell>
                  <div class="flex items-center space-x-2">
                    <Button variant="ghost" size="icon" @click="viewClient(client.id)">
                      <Eye class="h-4 w-4" />
                    </Button>
                    <Button variant="ghost" size="icon" @click="editClient(client.id)">
                      <Edit class="h-4 w-4" />
                    </Button>
                    <Button variant="ghost" size="icon" @click="deleteClient(client.id)">
                      <Trash2 class="h-4 w-4 text-destructive" />
                    </Button>
                  </div>
                </TableCell>
              </TableRow>
            </TableBody>
          </Table>
        </div>
      </CardContent>
      <CardFooter v-if="meta && meta.total > 0" class="flex items-center justify-between px-6 py-4">
        <div class="text-sm text-muted-foreground">
          Mostrando <span class="font-medium">{{ (meta.currentPage - 1) * meta.perPage + 1 }}</span> a 
          <span class="font-medium">{{ Math.min(meta.currentPage * meta.perPage, meta.total) }}</span> de 
          <span class="font-medium">{{ meta.total }}</span> clientes
        </div>
        <div class="flex space-x-2">
          <Button
            variant="outline"
            size="sm"
            :disabled="meta.currentPage === 1"
            @click="() => { if (filters.page) { filters.page--; goTo('clientes.index', { page: filters.page }); } }"
          >
            Anterior
          </Button>
          <Button
            variant="outline"
            size="sm"
            :disabled="meta.currentPage >= meta.lastPage"
            @click="() => { if (filters.page) { filters.page++; goTo('clientes.index', { page: filters.page }); } }"
          >
            Siguiente
          </Button>
        </div>
      </CardFooter>
    </Card>
  </div>
</template>
