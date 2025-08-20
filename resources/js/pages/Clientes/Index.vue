<script setup lang="ts">
import { ref, onMounted, computed } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { 
  PlusCircle, 
  Search, 
  Users, 
  TrendingUp, 
  Clock, 
  Download,
  RefreshCw,
  Mail,
  Phone,
  MapPin,
  Calendar,
  Star
} from 'lucide-vue-next';
import { Link } from '@inertiajs/vue3';
import { http } from '@/lib/axios';
import { clientService } from '@/services/clientService';
import type { Cliente, ClienteFilters } from '@/types/client';

interface ClienteStats {
  total: number;
  active: number;
  newThisMonth: number;
  totalOrders: number;
  averageOrderValue: number;
}

const clientes = ref<Cliente[]>([]);
const stats = ref<ClienteStats>({
  total: 0,
  active: 0,
  newThisMonth: 0,
  totalOrders: 0,
  averageOrderValue: 0
});
const searchTerm = ref('');
const isLoading = ref(false);
const currentPage = ref(1);
const totalPages = ref(1);
const perPage = ref(10);
const sortBy = ref<'nombre' | 'correo_electronico' | 'created_at'>('nombre');
const sortOrder = ref<'asc' | 'desc'>('asc');
const statusFilter = ref<'all' | 'active' | 'inactive'>('all');

const filters = computed<ClienteFilters>(() => ({
  search: searchTerm.value,
  sortBy: sortBy.value,
  sortOrder: sortOrder.value,
  page: currentPage.value,
  perPage: perPage.value
}));

const fetchClientes = async () => {
  isLoading.value = true;
  try {
    const response = await clientService.getClients(filters.value);
    clientes.value = response.data;
    if (response.meta) {
      totalPages.value = response.meta.last_page;
      currentPage.value = response.meta.current_page;
    }
  } catch (error) {
    console.error('Error fetching clients:', error);
  } finally {
    isLoading.value = false;
  }
};

const fetchStats = async () => {
  try {
    // En un proyecto real, esto vendría de un endpoint específico
    // Por ahora simulamos las estadísticas
    const total = clientes.value.length;
    stats.value = {
      total,
      active: Math.floor(total * 0.85),
      newThisMonth: Math.floor(total * 0.15),
      totalOrders: total * 3,
      averageOrderValue: 45.50
    };
  } catch (error) {
    console.error('Error fetching stats:', error);
  }
};

const deleteCliente = async (id: number) => {
  if (confirm('¿Estás seguro de que quieres eliminar este cliente?')) {
    try {
      await clientService.deleteClient(id);
      await fetchClientes();
      await fetchStats();
    } catch (error) {
      console.error('Error deleting client:', error);
    }
  }
};

const handleSearch = () => {
  currentPage.value = 1;
  fetchClientes();
};

const handleSort = (field: 'nombre' | 'correo_electronico' | 'created_at') => {
  if (sortBy.value === field) {
    sortOrder.value = sortOrder.value === 'asc' ? 'desc' : 'asc';
  } else {
    sortBy.value = field;
    sortOrder.value = 'asc';
  }
  fetchClientes();
};

const handlePageChange = (page: number) => {
  currentPage.value = page;
  fetchClientes();
};

const exportClients = () => {
  // Implementar exportación de clientes
  console.log('Exporting clients...');
};

const getClientInitials = (name: string) => {
  if (!name) return '';
  const names = name.split(' ');
  if (names.length === 1) return names[0].charAt(0).toUpperCase();
  return names[0].charAt(0).toUpperCase() + names[names.length - 1].charAt(0).toUpperCase();
};

const formatDate = (dateString: string) => {
  const date = new Date(dateString);
  return date.toLocaleDateString('es-ES', { month: 'numeric', day: 'numeric', hour: 'numeric', minute: 'numeric' });
};

onMounted(async () => {
  await fetchClientes();
  await fetchStats();
});
</script>

<template>
  <AppLayout>
    <div class="min-h-screen bg-gradient-to-br from-orange-50 to-orange-100 dark:from-gray-900 dark:to-orange-900/10">
      <!-- Header Section -->
      <div class="bg-white dark:bg-gray-800 shadow-lg border-b border-orange-200 dark:border-orange-800">
        <div class="max-w-7xl mx-auto px-6 py-8">
          <div class="flex items-center justify-between">
            <div>
              <h1 class="text-4xl font-bold text-gradient mb-2">
                👥 Clientes
              </h1>
              <p class="text-lg text-gray-600 dark:text-gray-400">
                Administra y monitorea todos los clientes de tu restaurante
              </p>
            </div>
            <div class="flex items-center space-x-3">
              <button
                @click="exportClients"
                class="btn-restaurant-secondary inline-flex items-center"
              >
                <Download class="w-5 h-5 mr-2" />
                Exportar
              </button>
              <Link :href="route('clientes.create')">
                <button class="btn-restaurant inline-flex items-center">
                  <PlusCircle class="w-5 h-5 mr-2" />
                  Nuevo Cliente
                </button>
              </Link>
            </div>
          </div>
        </div>
      </div>

      <!-- Content Section -->
      <div class="max-w-7xl mx-auto px-6 py-8">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
          <div class="restaurant-card p-6">
            <div class="flex items-center">
              <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                <Users class="w-6 h-6 text-blue-600 dark:text-blue-400" />
              </div>
              <div class="ml-4">
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Clientes</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.total }}</p>
              </div>
            </div>
          </div>
          
          <div class="restaurant-card p-6">
            <div class="flex items-center">
              <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                <TrendingUp class="w-6 h-6 text-green-600 dark:text-green-400" />
              </div>
              <div class="ml-4">
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Clientes Activos</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.active }}</p>
              </div>
            </div>
          </div>
          
          <div class="restaurant-card p-6">
            <div class="flex items-center">
              <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center">
                <Clock class="w-6 h-6 text-purple-600 dark:text-purple-400" />
              </div>
              <div class="ml-4">
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Nuevos este Mes</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.newThisMonth }}</p>
              </div>
            </div>
          </div>
          
          <div class="restaurant-card p-6">
            <div class="flex items-center">
              <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900/30 rounded-lg flex items-center justify-center">
                <Star class="w-6 h-6 text-orange-600 dark:text-orange-400" />
              </div>
              <div class="ml-4">
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Pedidos Totales</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.totalOrders }}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Filters and Controls -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-orange-200 dark:border-orange-800 p-6 mb-8">
          <div class="flex flex-col sm:flex-row items-center justify-between space-y-4 sm:space-y-0 sm:space-x-4">
            <!-- Search and Filters -->
            <div class="flex items-center space-x-4 flex-1">
              <div class="relative flex-1 max-w-md">
                <input
                  v-model="searchTerm"
                  type="text"
                  placeholder="Buscar por nombre, email o teléfono..."
                  class="input-restaurant w-full"
                />
                <Search class="absolute right-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" />
              </div>
              
              <select
                v-model="sortBy"
                class="input-restaurant max-w-xs"
              >
                <option value="nombre">Nombre</option>
                <option value="correo_electronico">Email</option>
                <option value="created_at">Fecha de Registro</option>
              </select>

              <select
                v-model="sortOrder"
                class="input-restaurant max-w-xs"
              >
                <option value="asc">Ascendente</option>
                <option value="desc">Descendente</option>
              </select>

              <select
                v-model="perPage"
                class="input-restaurant max-w-xs"
              >
                <option value="10">10 por página</option>
                <option value="25">25 por página</option>
                <option value="50">50 por página</option>
              </select>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center space-x-4">
              <button
                @click="handleSearch"
                class="btn-restaurant-secondary inline-flex items-center"
              >
                <Search class="w-4 h-4 mr-2" />
                Buscar
              </button>
              <button
                @click="fetchClientes"
                class="btn-restaurant-secondary inline-flex items-center"
              >
                <RefreshCw class="w-4 h-4 mr-2" />
                Actualizar
              </button>
              <span class="text-sm text-gray-600 dark:text-gray-400">
                {{ clientes.length }} clientes encontrados
              </span>
            </div>
          </div>
        </div>

        <!-- Clients Table -->
        <div v-if="clientes.length > 0">
          <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-orange-200 dark:border-orange-800 overflow-hidden">
            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-orange-200 dark:divide-orange-800">
                <thead class="bg-orange-50 dark:bg-orange-900/20">
                  <tr>
                    <th class="px-6 py-4 text-left text-xs font-bold text-orange-800 dark:text-orange-200 uppercase tracking-wider">
                      Cliente
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-orange-800 dark:text-orange-200 uppercase tracking-wider">
                      Contacto
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-orange-800 dark:text-orange-200 uppercase tracking-wider">
                      Dirección
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-orange-800 dark:text-orange-200 uppercase tracking-wider">
                      Registro
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-orange-800 dark:text-orange-200 uppercase tracking-wider">
                      Acciones
                    </th>
                  </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-orange-100 dark:divide-orange-800">
                  <tr v-for="cliente in clientes" :key="cliente.id" class="hover:bg-orange-50 dark:hover:bg-orange-900/10 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="flex items-center">
                        <div class="w-10 h-10 bg-orange-100 dark:bg-orange-900/30 rounded-full flex items-center justify-center">
                          <span class="text-sm font-bold text-orange-800 dark:text-orange-200">
                            {{ getClientInitials(cliente.nombre) }}
                          </span>
                        </div>
                        <div class="ml-4">
                          <div class="text-sm font-bold text-gray-900 dark:text-white">
                            {{ cliente.nombre }}
                          </div>
                          <div class="text-sm text-gray-600 dark:text-gray-400">
                            ID: #{{ cliente.id }}
                          </div>
                        </div>
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="space-y-1">
                        <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                          <Mail class="w-4 h-4 mr-2 text-orange-500" />
                          {{ cliente.correo_electronico }}
                        </div>
                        <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                          <Phone class="w-4 h-4 mr-2 text-orange-500" />
                          {{ cliente.telefono }}
                        </div>
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                        <MapPin class="w-4 h-4 mr-2 text-orange-500" />
                        {{ cliente.direccion }}
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                      <div class="flex items-center">
                        <Calendar class="w-4 h-4 mr-2 text-orange-500" />
                        {{ formatDate(cliente.created_at) }}
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                      <div class="flex items-center space-x-2">
                        <Link :href="route('clientes.show', cliente.id)">
                          <button class="btn-restaurant-secondary">
                            Ver Perfil
                          </button>
                        </Link>
                        <Link :href="route('clientes.edit', cliente.id)">
                          <button class="btn-restaurant-secondary">
                            Editar
                          </button>
                        </Link>
                        <button
                          @click="deleteCliente(cliente.id)"
                          class="btn-restaurant-secondary text-red-600 hover:text-red-700"
                        >
                          Eliminar
                        </button>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- Empty State -->
        <div v-else class="text-center py-12">
          <div class="w-24 h-24 mx-auto mb-4 bg-orange-100 dark:bg-orange-900/30 rounded-full flex items-center justify-center">
            <Users class="w-12 h-12 text-orange-500" />
          </div>
          <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No hay clientes</h3>
          <p class="text-gray-600 dark:text-gray-400 mb-6">Comienza agregando tu primer cliente</p>
          <Link :href="route('clientes.create')">
            <button class="btn-restaurant">
              <PlusCircle class="w-5 h-5 mr-2" />
              Nuevo Cliente
            </button>
          </Link>
        </div>

        <!-- Pagination -->
        <div v-if="totalPages > 1" class="mt-8 flex items-center justify-center space-x-2">
          <button
            @click="handlePageChange(currentPage - 1)"
            :disabled="currentPage === 1"
            class="btn-restaurant-secondary disabled:opacity-50 disabled:cursor-not-allowed"
          >
            Anterior
          </button>
          
          <span class="px-4 py-2 text-sm text-gray-600 dark:text-gray-400">
            Página {{ currentPage }} de {{ totalPages }}
          </span>
          
          <button
            @click="handlePageChange(currentPage + 1)"
            :disabled="currentPage === totalPages"
            class="btn-restaurant-secondary disabled:opacity-50 disabled:cursor-not-allowed"
          >
            Siguiente
          </button>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<style scoped>
.animate-pulse {
  animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

@keyframes pulse {
  0%, 100% {
    opacity: 1;
  }
  50% {
    opacity: .5;
  }
}
</style>