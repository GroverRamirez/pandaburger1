<script setup lang="ts">
import { ref, onMounted, computed, watch } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { 
  ShoppingCart, 
  Plus, 
  Search, 
  Filter,
  Download,
  RefreshCw,
  Clock,
  CheckCircle,
  XCircle,
  AlertCircle,
  TrendingUp,
  DollarSign,
  Calendar,
  User,
  Package,
  Eye,
  Edit,
  Trash2,
  MoreHorizontal,
  ChevronDown,
  ChevronUp
} from 'lucide-vue-next';
import { Link } from '@inertiajs/vue3';
import { pedidoService } from '@/services/pedidoService';
import type { Pedido, PedidoFilters, PedidoStats, EstadoPedido } from '@/types/pedidos';

interface PedidoWithCalculated extends Pedido {
  total_items: number;
  total_pedido: number;
  estado_nombre: string;
  cliente_nombre: string;
  usuario_nombre: string;
}

const pedidos = ref<PedidoWithCalculated[]>([]);
const response = ref<any>(null);
const stats = ref<PedidoStats>({
  total_pedidos: 0,
  pedidos_pendientes: 0,
  pedidos_en_proceso: 0,
  pedidos_completados: 0,
  pedidos_cancelados: 0,
  total_ventas: 0,
  promedio_pedido: 0,
  pedidos_hoy: 0,
  pedidos_semana: 0,
  pedidos_mes: 0
});
const estados = ref<EstadoPedido[]>([]);
const clientes = ref<any[]>([]);
const isLoading = ref(false);
const currentPage = ref(1);
const totalPages = ref(1);
const perPage = ref(10);
const showSuccessMessage = ref(false);

// Filtros
const filters = ref<PedidoFilters>({
  search: '',
  estado_id: undefined,
  cliente_id: undefined,
  fecha_desde: '',
  fecha_hasta: '',
  sortBy: 'fecha',
  sortOrder: 'desc',
  page: 1,
  perPage: 10
});

// Estados de filtros
const showFilters = ref(false);
const showAdvancedFilters = ref(false);

// Función para calcular páginas visibles en la paginación
const visiblePages = computed(() => {
  const pages = []
  const maxVisible = 5
  const start = Math.max(1, currentPage.value - Math.floor(maxVisible / 2))
  const end = Math.min(totalPages.value, start + maxVisible - 1)
  
  for (let i = start; i <= end; i++) {
    pages.push(i)
  }
  
  return pages
})

const fetchPedidos = async () => {
  isLoading.value = true;
  try {
    console.log('Fetching pedidos with filters:', filters.value);
    console.log('Página actual antes de fetch:', currentPage.value);
    
    const responseData = await pedidoService.getPedidos(filters.value);
    console.log('Response received:', responseData);
    console.log('Tipo de responseData:', typeof responseData);
    console.log('responseData.meta existe?', !!responseData.meta);
    console.log('responseData.meta:', responseData.meta);
    console.log('responseData.keys:', Object.keys(responseData));
    
    response.value = responseData;
    pedidos.value = responseData.data as PedidoWithCalculated[];
    console.log('Pedidos actualizados:', pedidos.value.length, 'pedidos');
    
    // Verificar si tenemos información de paginación
    const hasMeta = responseData.meta && typeof responseData.meta === 'object';
    const hasPaginationData = hasMeta && (
      responseData.meta.last_page !== undefined || 
      responseData.meta.current_page !== undefined ||
      responseData.meta.total !== undefined
    );
    
    // Verificación adicional: buscar paginación en diferentes ubicaciones
    const hasDirectPagination = (responseData as any).last_page !== undefined || 
                               (responseData as any).current_page !== undefined ||
                               (responseData as any).total !== undefined;
    
    console.log('Verificación de paginación:', {
      hasMeta,
      hasPaginationData,
      hasDirectPagination,
      metaKeys: hasMeta ? Object.keys(responseData.meta) : 'N/A',
      directKeys: Object.keys(responseData).filter(key => ['last_page', 'current_page', 'total', 'per_page'].includes(key))
    });
    
    if (hasPaginationData || hasDirectPagination) {
      const oldTotalPages = totalPages.value;
      const oldCurrentPage = currentPage.value;
      
      // Actualizar paginación con valores seguros
      if (hasPaginationData && responseData.meta) {
        if (responseData.meta.last_page !== undefined) {
          totalPages.value = responseData.meta.last_page;
        }
        if (responseData.meta.current_page !== undefined) {
          currentPage.value = responseData.meta.current_page;
        }
      } else if (hasDirectPagination) {
        // Usar datos de paginación directos si no hay meta
        if ((responseData as any).last_page !== undefined) {
          totalPages.value = (responseData as any).last_page;
        }
        if ((responseData as any).current_page !== undefined) {
          currentPage.value = (responseData as any).current_page;
        }
      }
      
      // Obtener valores para logging
      const total = responseData.meta?.total || (responseData as any).total;
      const perPage = responseData.meta?.per_page || (responseData as any).per_page;
      
      console.log('Paginación actualizada:', {
        totalPages: totalPages.value,
        currentPage: currentPage.value,
        total,
        perPage,
        oldTotalPages,
        oldCurrentPage
      });
      
      // Verificar si la paginación cambió significativamente
      if (oldTotalPages !== totalPages.value) {
        console.log('⚠️ Total de páginas cambió de', oldTotalPages, 'a', totalPages.value);
      }
      
      if (oldCurrentPage !== currentPage.value) {
        console.log('⚠️ Página actual cambió de', oldCurrentPage, 'a', currentPage.value);
      }
    } else {
      console.warn('No se recibió información de paginación válida en la respuesta');
      console.log('Respuesta completa:', responseData);
      
      // Establecer valores por defecto si no hay meta
      totalPages.value = 1;
      currentPage.value = 1;
    }
  } catch (error) {
    console.error('Error fetching orders:', error);
  } finally {
    isLoading.value = false;
  }
};

const fetchStats = async () => {
  try {
    const statsData = await pedidoService.getPedidoStats();
    stats.value = statsData;
  } catch (error) {
    console.error('Error fetching stats:', error);
  }
};

const fetchEstados = async () => {
  try {
    const estadosData = await pedidoService.getEstadosPedido();
    estados.value = estadosData;
  } catch (error) {
    console.error('Error fetching states:', error);
  }
};

// Watchers para resetear paginación cuando cambien los filtros
watch(() => filters.value.estado_id, (newValue, oldValue) => {
  if (newValue !== oldValue) {
    console.log('Estado cambiado, reseteando paginación');
    filters.value.page = 1;
    currentPage.value = 1;
    fetchPedidos();
  }
});

watch(() => filters.value.search, (newValue, oldValue) => {
  if (newValue !== oldValue) {
    console.log('Búsqueda cambiada, reseteando paginación');
    filters.value.page = 1;
    currentPage.value = 1;
  }
});

watch(() => filters.value.fecha_desde, (newValue, oldValue) => {
  if (newValue !== oldValue) {
    console.log('Fecha desde cambiada, reseteando paginación');
    filters.value.page = 1;
    currentPage.value = 1;
  }
});

watch(() => filters.value.fecha_hasta, (newValue, oldValue) => {
  if (newValue !== oldValue) {
    console.log('Fecha hasta cambiada, reseteando paginación');
    filters.value.page = 1;
    currentPage.value = 1;
  }
});

const handleSearch = () => {
  filters.value.page = 1;
  currentPage.value = 1;
  fetchPedidos();
};

const handlePageChange = (page: number) => {
  currentPage.value = page;
  filters.value.page = page;
  fetchPedidos();
};

const exportPedidos = async (format: 'pdf' | 'excel' = 'pdf') => {
  try {
    const blob = await pedidoService.exportPedidos(filters.value, format) as Blob;
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `pedidos-${new Date().toISOString().split('T')[0]}.${format}`;
    a.click();
    window.URL.revokeObjectURL(url);
  } catch (error) {
    console.error('Error exporting orders:', error);
  }
};

const deletePedido = async (id: number) => {
  if (confirm('¿Estás seguro de que quieres eliminar este pedido?')) {
    try {
      await pedidoService.deletePedido(id);
      await fetchPedidos();
      await fetchStats();
    } catch (error) {
      console.error('Error deleting order:', error);
    }
  }
};

const getEstadoColor = (estadoNombre: string) => {
  const estado = estados.value.find(e => e.nombre === estadoNombre);
  return estado?.color || 'bg-gray-500';
};

const getEstadoIcon = (estadoNombre: string) => {
  switch (estadoNombre.toLowerCase()) {
    case 'pendiente':
      return Clock;
    case 'en proceso':
      return AlertCircle;
    case 'completado':
      return CheckCircle;
    case 'cancelado':
      return XCircle;
    default:
      return Package;
  }
};

const formatDate = (dateString: string) => {
  const date = new Date(dateString);
  return date.toLocaleDateString('es-ES', { 
    year: 'numeric', 
    month: 'short', 
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  });
};

const formatCurrency = (amount: number) => {
  return new Intl.NumberFormat('es-BO', {
    style: 'currency',
    currency: 'BOB'
  }).format(amount);
};

onMounted(async () => {
  // Verificar si se acaba de crear un pedido
  const urlParams = new URLSearchParams(window.location.search);
  if (urlParams.get('created') === '1') {
    // Resetear filtros para mostrar el pedido más reciente
    filters.value = {
      search: '',
      estado_id: undefined,
      cliente_id: undefined,
      fecha_desde: '',
      fecha_hasta: '',
      sortBy: 'fecha',
      sortOrder: 'desc',
      page: 1,
      perPage: 10
    };
    
    // Limpiar la URL
    window.history.replaceState({}, '', route('pedidos.index'));
    
    console.log('Pedido creado exitosamente! Actualizando lista...');
    showSuccessMessage.value = true;
    
    // Ocultar el mensaje después de 5 segundos
    setTimeout(() => {
      showSuccessMessage.value = false;
    }, 5000);
    
    // Forzar la actualización de la lista con los filtros reseteados
    await fetchPedidos();
    await fetchStats();
  }
  
  await Promise.all([
    fetchPedidos(),
    fetchStats(),
    fetchEstados()
  ]);
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
                🛒 Pedidos
              </h1>
              <p class="text-lg text-gray-600 dark:text-gray-400">
                Gestiona y monitorea todos los pedidos de tu restaurante
              </p>
            </div>
            <div class="flex items-center space-x-3">
              <button
                @click="exportPedidos('pdf')"
                class="btn-restaurant-secondary inline-flex items-center"
              >
                <Download class="w-5 h-5 mr-2" />
                Exportar PDF
              </button>
              <button
                @click="exportPedidos('excel')"
                class="btn-restaurant-secondary inline-flex items-center"
              >
                <Download class="w-5 h-5 mr-2" />
                Exportar Excel
              </button>
              <Link :href="route('pedidos.create')">
                <button class="btn-restaurant inline-flex items-center">
                  <Plus class="w-5 h-5 mr-2" />
                  Nuevo Pedido
                </button>
              </Link>
            </div>
          </div>
        </div>
      </div>

      <!-- Success Message -->
      <div v-if="showSuccessMessage" class="fixed top-4 right-4 z-50">
        <div class="bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg flex items-center space-x-3">
          <CheckCircle class="w-6 h-6" />
          <span class="font-medium">¡Pedido creado exitosamente!</span>
          <button @click="showSuccessMessage = false" class="ml-4 text-white hover:text-gray-200">
            <XCircle class="w-5 h-5" />
          </button>
        </div>
      </div>

      <!-- Content Section -->
      <div class="max-w-7xl mx-auto px-6 py-8">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
          <div class="restaurant-card p-6">
            <div class="flex items-center">
              <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                <ShoppingCart class="w-6 h-6 text-blue-600 dark:text-blue-400" />
              </div>
              <div class="ml-4">
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Pedidos</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.total_pedidos }}</p>
              </div>
            </div>
          </div>
          
          <div class="restaurant-card p-6">
            <div class="flex items-center">
              <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                <CheckCircle class="w-6 h-6 text-green-600 dark:text-green-400" />
              </div>
              <div class="ml-4">
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Completados</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.pedidos_completados }}</p>
              </div>
            </div>
          </div>
          
          <div class="restaurant-card p-6">
            <div class="flex items-center">
              <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900/30 rounded-lg flex items-center justify-center">
                <AlertCircle class="w-6 h-6 text-orange-600 dark:text-orange-400" />
              </div>
              <div class="ml-4">
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">En Proceso</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.pedidos_en_proceso }}</p>
              </div>
            </div>
          </div>
          
          <div class="restaurant-card p-6">
            <div class="flex items-center">
              <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center">
                <DollarSign class="w-6 h-6 text-purple-600 dark:text-purple-400" />
              </div>
              <div class="ml-4">
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Ventas</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ formatCurrency(stats.total_ventas) }}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Additional Stats Row -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
          <div class="restaurant-card p-6">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Pedidos Hoy</p>
                <p class="text-xl font-bold text-gray-900 dark:text-white">{{ stats.pedidos_hoy }}</p>
              </div>
              <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                <Calendar class="w-6 h-6 text-blue-600 dark:text-blue-400" />
              </div>
            </div>
          </div>
          
          <div class="restaurant-card p-6">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Promedio Pedido</p>
                <p class="text-xl font-bold text-gray-900 dark:text-white">{{ formatCurrency(stats.promedio_pedido) }}</p>
              </div>
              <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                <TrendingUp class="w-6 h-6 text-green-600 dark:text-green-400" />
              </div>
            </div>
          </div>
          
          <div class="restaurant-card p-6">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Pendientes</p>
                <p class="text-xl font-bold text-gray-900 dark:text-white">{{ stats.pedidos_pendientes }}</p>
              </div>
              <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg flex items-center justify-center">
                <Clock class="w-6 h-6 text-yellow-600 dark:text-yellow-400" />
              </div>
            </div>
          </div>
        </div>

        <!-- Filters and Controls -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-orange-200 dark:border-orange-800 p-6 mb-8">
          <div class="flex flex-col space-y-4">
            <!-- Basic Filters -->
            <div class="flex flex-col sm:flex-row items-center justify-between space-y-4 sm:space-y-0 sm:space-x-4">
              <div class="flex items-center space-x-4 flex-1">
                <div class="relative flex-1 max-w-md">
                  <input
                    v-model="filters.search"
                    type="text"
                    placeholder="Buscar por ID, cliente o productos..."
                    class="input-restaurant w-full"
                  />
                  <Search class="absolute right-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" />
                </div>
                
                <select
                  v-model="filters.estado_id"
                  class="input-restaurant max-w-xs"
                >
                  <option value="">Todos los estados</option>
                  <option
                    v-for="estado in estados"
                    :key="estado.id"
                    :value="estado.id"
                  >
                    {{ estado.nombre }}
                  </option>
                </select>

                <select
                  v-model="filters.sortBy"
                  class="input-restaurant max-w-xs"
                >
                  <option value="fecha">Fecha</option>
                  <option value="total">Total</option>
                  <option value="estado">Estado</option>
                  <option value="cliente">Cliente</option>
                </select>

                <select
                  v-model="filters.sortOrder"
                  class="input-restaurant max-w-xs"
                >
                  <option value="desc">Descendente</option>
                  <option value="asc">Ascendente</option>
                </select>
              </div>

              <!-- Action Buttons -->
              <div class="flex items-center space-x-4">
                <button
                  @click="showAdvancedFilters = !showAdvancedFilters"
                  class="btn-restaurant-secondary inline-flex items-center"
                >
                  <Filter class="w-4 h-4 mr-2" />
                  {{ showAdvancedFilters ? 'Ocultar' : 'Filtros Avanzados' }}
                </button>
                <button
                  @click="handleSearch"
                  class="btn-restaurant-secondary inline-flex items-center"
                >
                  <Search class="w-4 h-4 mr-2" />
                  Buscar
                </button>
                <button
                  @click="fetchPedidos"
                  class="btn-restaurant-secondary inline-flex items-center"
                >
                  <RefreshCw class="w-4 h-4 mr-2" />
                  Actualizar
                </button>
              </div>
            </div>

            <!-- Advanced Filters -->
            <div v-if="showAdvancedFilters" class="border-t border-gray-200 dark:border-gray-700 pt-4">
              <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Fecha Desde
                  </label>
                  <input
                    v-model="filters.fecha_desde"
                    type="date"
                    class="input-restaurant w-full"
                  />
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Fecha Hasta
                  </label>
                  <input
                    v-model="filters.fecha_hasta"
                    type="date"
                    class="input-restaurant w-full"
                  />
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Por Página
                  </label>
                  <select
                    v-model="filters.perPage"
                    class="input-restaurant w-full"
                  >
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                  </select>
                </div>
              </div>
            </div>

                         <!-- Results Count -->
             <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-700">
               <span class="text-sm text-gray-600 dark:text-gray-400">
                 {{ pedidos?.length || 0 }} pedidos encontrados
                 <span v-if="response?.meta?.total" class="text-xs text-gray-500">
                   (de {{ response.meta.total }} total)
                 </span>
               </span>
               <div class="flex items-center space-x-2">
                 <span class="text-sm text-gray-600 dark:text-gray-400">
                   Página {{ currentPage }} de {{ totalPages }}
                 </span>
                 <span v-if="response?.meta?.total && totalPages > 1" class="text-xs text-gray-500">
                   ({{ response.meta.per_page }} por página)
                 </span>
               </div>
             </div>
          </div>
        </div>

        <!-- Orders Table -->
        <div v-if="pedidos && pedidos.length > 0">
          <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-orange-200 dark:border-orange-800 overflow-hidden">
            <div class="overflow-x-auto scrollbar-thin scrollbar-thumb-orange-300 scrollbar-track-gray-100 dark:scrollbar-thumb-orange-600 dark:scrollbar-track-gray-700">
              <table class="orders-table w-full table-fixed divide-y divide-orange-200 dark:divide-orange-800">
                <thead class="bg-orange-50 dark:bg-orange-900/20">
                  <tr>
                    <th class="w-32 px-3 py-4 text-left text-xs font-bold text-orange-800 dark:text-orange-200 uppercase tracking-wider">
                      Pedido
                    </th>
                    <th class="w-44 px-3 py-4 text-left text-xs font-bold text-orange-800 dark:text-orange-200 uppercase tracking-wider">
                      Cliente
                    </th>
                    <th class="w-36 px-3 py-4 text-left text-xs font-bold text-orange-800 dark:text-orange-200 uppercase tracking-wider">
                      Estado
                    </th>
                    <th class="w-28 px-3 py-4 text-left text-xs font-bold text-orange-800 dark:text-orange-200 uppercase tracking-wider">
                      Productos
                    </th>
                    <th class="w-28 px-3 py-4 text-left text-xs font-bold text-orange-800 dark:text-orange-200 uppercase tracking-wider">
                      Total
                    </th>
                    <th class="w-36 px-3 py-4 text-left text-xs font-bold text-orange-800 dark:text-orange-200 uppercase tracking-wider">
                      Fecha
                    </th>
                    <th class="w-60 px-3 py-4 text-left text-xs font-bold text-orange-800 dark:text-orange-200 uppercase tracking-wider">
                      Acciones
                    </th>
                  </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-orange-100 dark:divide-orange-800">
                  <tr v-for="pedido in pedidos" :key="pedido.id" class="hover:bg-orange-50 dark:hover:bg-orange-900/10 transition-colors">
                    <td class="w-32 px-3 py-4">
                      <div class="flex items-center justify-center">
                        <div class="w-8 h-8 bg-orange-100 dark:bg-orange-900/30 rounded-lg flex items-center justify-center">
                          <span class="text-xs font-bold text-orange-800 dark:text-orange-200">
                            #{{ pedido.id }}
                          </span>
                        </div>
                      </div>
                    </td>
                    <td class="w-44 px-3 py-4">
                      <div class="flex items-center">
                        <User class="w-4 h-4 mr-2 text-orange-500 flex-shrink-0" />
                        <span class="text-sm text-gray-900 dark:text-white truncate">
                          {{ pedido.cliente_nombre || 'Sin cliente' }}
                        </span>
                      </div>
                    </td>
                    <td class="w-36 px-3 py-4">
                      <span
                        :class="[
                          'inline-flex items-center px-2 py-1 rounded-full text-xs font-bold whitespace-nowrap',
                          getEstadoColor(pedido.estado_nombre)
                        ]"
                      >
                        <component :is="getEstadoIcon(pedido.estado_nombre)" class="w-3 h-3 mr-1 flex-shrink-0" />
                        <span class="truncate">{{ pedido.estado_nombre }}</span>
                      </span>
                    </td>
                    <td class="w-28 px-3 py-4">
                      <div class="flex items-center justify-center text-sm text-gray-600 dark:text-gray-400">
                        <Package class="w-4 h-4 mr-1 text-orange-500 flex-shrink-0" />
                        <span class="truncate">{{ pedido.total_items || 0 }}</span>
                      </div>
                    </td>
                    <td class="w-28 px-3 py-4">
                      <div class="text-sm font-bold text-gray-900 dark:text-white truncate text-center">
                        {{ formatCurrency(pedido.total_pedido || 0) }}
                      </div>
                    </td>
                    <td class="w-36 px-3 py-4">
                      <div class="flex items-center">
                        <Calendar class="w-4 h-4 mr-2 text-orange-500 flex-shrink-0" />
                        <span class="text-xs text-gray-600 dark:text-gray-400 truncate">
                          {{ formatDate(pedido.fecha) }}
                        </span>
                      </div>
                    </td>
                    <td class="w-60 px-3 py-4">
                      <div class="flex items-center justify-start space-x-1">
                        <Link :href="route('pedidos.show', pedido.id)">
                          <button class="inline-flex items-center px-3 py-1.5 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors whitespace-nowrap">
                            <Eye class="w-3 h-3 mr-1" />
                            Ver
                          </button>
                        </Link>
                        <Link :href="route('pedidos.edit', pedido.id)">
                          <button class="inline-flex items-center px-3 py-1.5 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors whitespace-nowrap">
                            <Edit class="w-3 h-3 mr-1" />
                            Editar
                          </button>
                        </Link>
                        <button
                          @click="deletePedido(pedido.id)"
                          class="inline-flex items-center px-3 py-1.5 border border-red-600 rounded-md text-sm font-medium text-white bg-red-600 hover:bg-red-700 transition-colors whitespace-nowrap"
                        >
                          <Trash2 class="w-3 h-3 mr-1" />
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
        <div v-else-if="!isLoading" class="text-center py-12">
          <div class="w-24 h-24 mx-auto mb-4 bg-orange-100 dark:bg-orange-900/30 rounded-full flex items-center justify-center">
            <ShoppingCart class="w-12 h-12 text-orange-500" />
          </div>
          <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No hay pedidos</h3>
          <p class="text-gray-600 dark:text-gray-400 mb-6">Comienza creando tu primer pedido</p>
          <Link :href="route('pedidos.create')">
            <button class="btn-restaurant">
              <Plus class="w-5 h-5 mr-2" />
              Nuevo Pedido
            </button>
          </Link>
        </div>

        <!-- Loading State -->
        <div v-if="isLoading" class="text-center py-12">
          <div class="w-24 h-24 mx-auto mb-4 bg-orange-100 dark:bg-orange-900/30 rounded-full flex items-center justify-center">
            <RefreshCw class="w-12 h-12 text-orange-500 animate-spin" />
          </div>
          <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Cargando pedidos...</h3>
          <p class="text-gray-600 dark:text-gray-400">Por favor espera un momento</p>
        </div>

        <!-- Pagination -->
        <div v-if="totalPages > 1" class="bg-orange-50 dark:bg-orange-900/20 px-6 py-4 flex items-center justify-between border-t border-orange-200 dark:border-orange-800 rounded-b-lg">
          <div class="flex items-center space-x-2">
            <button
              @click="handlePageChange(currentPage - 1)"
              :disabled="currentPage === 1"
              class="btn-restaurant-secondary disabled:opacity-50 disabled:cursor-not-allowed"
            >
              Anterior
            </button>
            
            <div class="flex items-center space-x-1">
              <button
                v-for="page in visiblePages"
                :key="page"
                @click="handlePageChange(page)"
                :class="[
                  'px-3 py-2 rounded-lg text-sm font-medium transition-colors',
                  page === currentPage
                    ? 'bg-orange-600 text-white'
                    : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'
                ]"
              >
                {{ page }}
              </button>
            </div>
            
            <button
              @click="handlePageChange(currentPage + 1)"
              :disabled="currentPage === totalPages"
              class="btn-restaurant-secondary disabled:opacity-50 disabled:cursor-not-allowed"
            >
              Siguiente
            </button>
          </div>
          
          <!-- Pagination Info -->
          <div class="text-sm text-gray-600 dark:text-gray-400">
            <span v-if="pedidos && pedidos.length > 0">
              Mostrando {{ ((currentPage - 1) * (filters.perPage || 10)) + 1 }} - {{ Math.min(currentPage * (filters.perPage || 10), response?.meta?.total || pedidos.length) }} de {{ response?.meta?.total || pedidos.length }} pedidos
            </span>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<style scoped>
/* Estilos personalizados para la tabla de pedidos */
.orders-table {
  min-width: 1100px;
}

/* Responsive adjustments */
@media (max-width: 1400px) {
  .orders-table {
    min-width: 1000px;
  }
}

@media (max-width: 1200px) {
  .orders-table {
    min-width: 900px;
  }
}

@media (max-width: 1024px) {
  .orders-table {
    min-width: 800px;
  }
}

/* Mejorar la legibilidad de las celdas */
.table-cell {
  word-break: break-word;
  hyphens: auto;
}

/* Asegurar que los botones de acción no se compriman */
.action-buttons {
  flex-shrink: 0;
  white-space: nowrap;
}

/* Mejorar el scroll horizontal */
.overflow-x-auto {
  scrollbar-width: thin;
}

/* Estilos para mejor alineación */
.orders-table th,
.orders-table td {
  vertical-align: middle;
}

/* Mejorar la compacidad de los botones */
.orders-table .btn-restaurant-secondary {
  font-size: 0.75rem;
  padding: 0.25rem 0.5rem;
  min-width: auto;
}

/* Centrar contenido en columnas numéricas */
.orders-table .text-center {
  text-align: center;
}
</style>
