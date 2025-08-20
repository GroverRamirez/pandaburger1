<script setup lang="ts">
import { ref, onMounted } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { 
  ArrowLeft,
  Edit,
  Eye,
  ShoppingCart,
  User,
  Calendar,
  Package,
  DollarSign,
  Clock,
  CheckCircle,
  XCircle,
  AlertCircle,
  TrendingUp,
  Save,
  X
} from 'lucide-vue-next';
import { Link, router, Head } from '@inertiajs/vue3';
import { pedidoService } from '@/services/pedidoService';
import type { PedidoWithDetails, PedidoFormData } from '@/types/pedidos';

const props = defineProps<{
  pedidoId: string;
}>();

const pedido = ref<PedidoWithDetails | null>(null);
const isLoading = ref(true);
const error = ref<string | null>(null);
const isSaving = ref(false);

// Form data
const formData = ref<PedidoFormData>({
  cliente_id: null,
  estado_id: 1,
  fecha: new Date().toISOString().split('T')[0],
  detalles: []
});

const breadcrumbs = [
  { name: 'Inicio', href: route('dashboard') },
  { name: 'Pedidos', href: route('pedidos.index') },
  { name: 'Editar Pedido', href: '#' }
];

const fetchPedido = async () => {
  try {
    error.value = null;
    console.log('Fetching pedido for edit with ID:', props.pedidoId);
    const pedidoData = await pedidoService.getPedidoWithDetails(parseInt(props.pedidoId));
    console.log('Pedido data for edit received:', pedidoData);
    pedido.value = pedidoData;
    
    // Populate form data
    formData.value = {
      cliente_id: pedidoData.cliente_id,
      estado_id: pedidoData.estado_id,
      fecha: pedidoData.fecha,
      detalles: pedidoData.detallesPedido?.map(detalle => ({
        producto_id: detalle.producto_id,
        cantidad: detalle.cantidad,
        precio_unitario: detalle.precio_unitario,
        precio_total: detalle.precio_total
      })) || []
    };
  } catch (error: any) {
    console.error('Error fetching order for edit:', error);
    error.value = `Error al cargar el pedido: ${error.response?.status || 'Desconocido'} - ${error.message || 'Error de conexión'}`;
  } finally {
    isLoading.value = false;
  }
};

const handleSubmit = async () => {
  try {
    isSaving.value = true;
    console.log('Submitting pedido update:', formData.value);
    
    await pedidoService.updatePedido(parseInt(props.pedidoId), formData.value);
    
    // Redirect to show page with success message
    router.visit(route('pedidos.show', props.pedidoId), {
      data: { updated: 1 }
    });
  } catch (error: any) {
    console.error('Error updating order:', error);
    error.value = `Error al actualizar el pedido: ${error.response?.status || 'Desconocido'} - ${error.message || 'Error de conexión'}`;
  } finally {
    isSaving.value = false;
  }
};

const handleCancel = () => {
  router.visit(route('pedidos.show', props.pedidoId));
};

const getEstadoColor = (estadoNombre: string) => {
  switch (estadoNombre.toLowerCase()) {
    case 'pendiente':
      return 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400';
    case 'en proceso':
    case 'en preparación':
      return 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400';
    case 'completado':
    case 'listo':
    case 'entregado':
      return 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400';
    case 'cancelado':
      return 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400';
    default:
      return 'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400';
  }
};

const getEstadoIcon = (estadoNombre: string) => {
  switch (estadoNombre.toLowerCase()) {
    case 'pendiente':
      return Clock;
    case 'en proceso':
    case 'en preparación':
      return AlertCircle;
    case 'completado':
    case 'listo':
    case 'entregado':
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
    month: 'long', 
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

onMounted(() => {
  fetchPedido();
});
</script>

<template>
  <AppLayout>
    <Head title="Editar Pedido - Pandaburger" />

    <div class="min-h-screen bg-gradient-to-br from-orange-50 to-orange-100 dark:from-gray-900 dark:to-orange-900/10">
      <!-- Header Section -->
      <div class="bg-white dark:bg-gray-800 shadow-lg border-b border-orange-200 dark:border-orange-800">
        <div class="max-w-7xl mx-auto px-6 py-8">
          <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
              <Link :href="route('pedidos.show', pedidoId)">
                <button class="btn-restaurant-secondary inline-flex items-center">
                  <ArrowLeft class="w-5 h-5 mr-2" />
                  Volver
                </button>
              </Link>
              <div>
                <h1 class="text-4xl font-bold text-gradient mb-2">
                  ✏️ Editar Pedido #{{ pedido ? pedido.id : '...' }}
                </h1>
                <p class="text-lg text-gray-600 dark:text-gray-400">
                  Modifica la información del pedido
                </p>
              </div>
            </div>
            <div class="flex items-center space-x-3">
              <Link v-if="pedido" :href="route('pedidos.show', pedido.id)">
                <button class="btn-restaurant-secondary inline-flex items-center">
                  <Eye class="w-4 h-4 mr-2" />
                  Ver Pedido
                </button>
              </Link>
            </div>
          </div>
        </div>
      </div>

      <!-- Content Section -->
      <div class="max-w-7xl mx-auto px-6 py-8">
        <div v-if="isLoading" class="text-center py-12">
          <div class="w-24 h-24 mx-auto mb-4 bg-orange-100 dark:bg-orange-900/30 rounded-full flex items-center justify-center">
            <Clock class="w-12 h-12 text-orange-500 animate-spin" />
          </div>
          <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Cargando pedido...</h3>
          <p class="text-gray-600 dark:text-gray-400">Por favor espera un momento</p>
        </div>

        <div v-else-if="error" class="text-center py-12">
          <div class="w-24 h-24 mx-auto mb-4 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center">
            <XCircle class="w-12 h-12 text-red-500" />
          </div>
          <h3 class="text-lg font-medium text-red-900 dark:text-red-100 mb-2">Error al cargar el pedido</h3>
          <p class="text-red-600 dark:text-red-400 mb-4">{{ error }}</p>
          <button 
            @click="fetchPedido" 
            class="btn-restaurant-primary"
          >
            Reintentar
          </button>
        </div>

        <div v-else-if="pedido" class="space-y-6">
          <!-- Order Status Banner -->
          <div class="restaurant-card p-6">
            <div class="flex items-center justify-between">
              <div class="flex items-center space-x-4">
                <div class="w-16 h-16 bg-orange-100 dark:bg-orange-900/30 rounded-full flex items-center justify-center">
                  <component :is="getEstadoIcon(pedido.estado?.nombre || '')" class="w-8 h-8 text-orange-600 dark:text-orange-400" />
                </div>
                <div>
                  <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                    Pedido #{{ pedido.id }}
                  </h2>
                  <span
                    :class="[
                      'inline-flex items-center px-4 py-2 rounded-full text-sm font-bold',
                      getEstadoColor(pedido.estado?.nombre || '')
                    ]"
                  >
                    <component :is="getEstadoIcon(pedido.estado?.nombre || '')" class="w-4 h-4 mr-2" />
                    {{ pedido.estado?.nombre }}
                  </span>
                </div>
              </div>
              <div class="text-right">
                <p class="text-sm text-gray-600 dark:text-gray-400">Total del Pedido</p>
                <p class="text-3xl font-bold text-orange-600 dark:text-orange-400">
                  {{ formatCurrency(pedido.estadisticas?.total_final || 0) }}
                </p>
              </div>
            </div>
          </div>

          <!-- Edit Form -->
          <div class="restaurant-card p-6">
            <div class="text-center mb-6">
              <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                <Edit class="w-10 h-10 text-white" />
              </div>
              <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                Información del Pedido
              </h3>
              <p class="text-gray-600 dark:text-gray-400">
                Modifica los detalles de tu pedido
              </p>
            </div>

            <!-- Basic Form -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                  Cliente
                </label>
                <input
                  v-model="formData.cliente_id"
                  type="number"
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-700 dark:text-white"
                  placeholder="ID del cliente"
                />
              </div>
              
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                  Estado
                </label>
                <select
                  v-model="formData.estado_id"
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-700 dark:text-white"
                >
                  <option value="1">Pendiente</option>
                  <option value="2">En Preparación</option>
                  <option value="3">Listo</option>
                  <option value="4">Entregado</option>
                  <option value="5">Cancelado</option>
                </select>
              </div>
              
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                  Fecha
                </label>
                <input
                  v-model="formData.fecha"
                  type="date"
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-700 dark:text-white"
                />
              </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-center space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
              <button
                @click="handleCancel"
                class="btn-restaurant-secondary inline-flex items-center"
                :disabled="isSaving"
              >
                <X class="w-4 h-4 mr-2" />
                Cancelar
              </button>
              <button
                @click="handleSubmit"
                class="btn-restaurant inline-flex items-center"
                :disabled="isSaving"
              >
                <Save class="w-4 h-4 mr-2" />
                {{ isSaving ? 'Guardando...' : 'Guardar Cambios' }}
              </button>
            </div>
          </div>

          <!-- Current Order Details -->
          <div class="restaurant-card p-6">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4 flex items-center">
              <Package class="w-5 h-5 mr-2 text-orange-500" />
              Detalles Actuales del Pedido
            </h3>
            
            <div class="space-y-4">
              <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="flex items-center space-x-3">
                  <User class="w-5 h-5 text-orange-500" />
                  <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Cliente</p>
                    <p class="font-medium text-gray-900 dark:text-white">
                      {{ pedido.cliente?.nombre || 'Sin cliente' }}
                    </p>
                  </div>
                </div>
                
                <div class="flex items-center space-x-3">
                  <Calendar class="w-5 h-5 text-orange-500" />
                  <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Fecha</p>
                    <p class="font-medium text-gray-900 dark:text-white">
                      {{ formatDate(pedido.fecha) }}
                    </p>
                  </div>
                </div>
                
                <div class="flex items-center space-x-3">
                  <Package class="w-5 h-5 text-orange-500" />
                  <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Productos</p>
                    <p class="font-medium text-gray-900 dark:text-white">
                      {{ pedido.detallesPedido?.length || 0 }} productos
                    </p>
                  </div>
                </div>
              </div>
              
              <!-- Products List -->
              <div class="mt-6">
                <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">
                  Productos del Pedido
                </h4>
                <div class="space-y-2">
                  <div 
                    v-for="detalle in pedido.detallesPedido" 
                    :key="detalle.id"
                    class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-800 rounded-lg"
                  >
                    <div class="flex items-center space-x-3">
                      <Package class="w-4 h-4 text-orange-500" />
                      <span class="font-medium text-gray-900 dark:text-white">
                        {{ detalle.producto?.nombre || 'Producto desconocido' }}
                      </span>
                    </div>
                    <div class="flex items-center space-x-4 text-sm text-gray-600 dark:text-gray-400">
                      <span>Cantidad: {{ detalle.cantidad }}</span>
                      <span>Precio: {{ formatCurrency(detalle.precio_unitario) }}</span>
                      <span class="font-medium text-orange-600 dark:text-orange-400">
                        Total: {{ formatCurrency(detalle.precio_total) }}
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
