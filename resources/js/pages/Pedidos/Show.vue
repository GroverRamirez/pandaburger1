<script setup lang="ts">
import { ref, onMounted } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { 
  ArrowLeft,
  Edit,
  Trash2,
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
  MessageSquare,
  Download,
  Printer,
  Share2,
  Eye,
  MapPin,
  Phone,
  Mail,
  CreditCard,
  Truck,
  Star
} from 'lucide-vue-next';
import { Link, router } from '@inertiajs/vue3';
import { pedidoService } from '@/services/pedidoService';
import type { PedidoWithDetails, PedidoTimeline } from '@/types/pedidos';

const props = defineProps<{
  pedidoId: string;
}>();

const pedido = ref<PedidoWithDetails | null>(null);
const isLoading = ref(true);
const showTimeline = ref(true);
const showDetails = ref(true);
const showProducts = ref(true);
const showActions = ref(true);

const fetchPedido = async () => {
  try {
    const pedidoData = await pedidoService.getPedidoWithDetails(parseInt(props.pedidoId));
    pedido.value = pedidoData;
  } catch (error) {
    console.error('Error fetching order:', error);
  } finally {
    isLoading.value = false;
  }
};

const deletePedido = async () => {
  if (confirm('¿Estás seguro de que quieres eliminar este pedido?')) {
    try {
      await pedidoService.deletePedido(parseInt(props.pedidoId));
      router.visit(route('pedidos.index'));
    } catch (error) {
      console.error('Error deleting order:', error);
    }
  }
};

const changeEstado = async (nuevoEstadoId: number) => {
  try {
    await pedidoService.changeEstado(parseInt(props.pedidoId), nuevoEstadoId);
    await fetchPedido(); // Recargar datos
  } catch (error) {
    console.error('Error changing state:', error);
  }
};

const getEstadoColor = (estadoNombre: string) => {
  switch (estadoNombre.toLowerCase()) {
    case 'pendiente':
      return 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400';
    case 'en proceso':
      return 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400';
    case 'completado':
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

const printPedido = () => {
  window.print();
};

const exportPedido = async (format: 'pdf' | 'excel' = 'pdf') => {
  try {
    const blob = await pedidoService.exportPedidos({}, format);
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `pedido-${pedido.value?.id}-${new Date().toISOString().split('T')[0]}.${format}`;
    a.click();
    window.URL.revokeObjectURL(url);
  } catch (error) {
    console.error('Error exporting order:', error);
  }
};

onMounted(() => {
  fetchPedido();
});
</script>

<template>
  <AppLayout>
    <div class="min-h-screen bg-gradient-to-br from-orange-50 to-orange-100 dark:from-gray-900 dark:to-orange-900/10">
      <!-- Header Section -->
      <div class="bg-white dark:bg-gray-800 shadow-lg border-b border-orange-200 dark:border-orange-800">
        <div class="max-w-7xl mx-auto px-6 py-8">
          <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
              <Link :href="route('pedidos.index')">
                <button class="btn-restaurant-secondary inline-flex items-center">
                  <ArrowLeft class="w-5 h-5 mr-2" />
                  Volver
                </button>
              </Link>
              <div>
                <h1 class="text-4xl font-bold text-gradient mb-2">
                  🛒 Pedido #{{ pedido?.id }}
                </h1>
                <p class="text-lg text-gray-600 dark:text-gray-400">
                  Detalles completos del pedido
                </p>
              </div>
            </div>
            <div class="flex items-center space-x-3">
              <button
                @click="exportPedido('pdf')"
                class="btn-restaurant-secondary inline-flex items-center"
              >
                <Download class="w-4 h-4 mr-2" />
                PDF
              </button>
              <button
                @click="printPedido"
                class="btn-restaurant-secondary inline-flex items-center"
              >
                <Printer class="w-4 h-4 mr-2" />
                Imprimir
              </button>
              <Link :href="route('pedidos.edit', pedido?.id)">
                <button class="btn-restaurant inline-flex items-center">
                  <Edit class="w-4 h-4 mr-2" />
                  Editar
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

          <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column - Order Details -->
            <div class="lg:col-span-2 space-y-6">
              <!-- Order Information -->
              <div class="restaurant-card p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                  <ShoppingCart class="w-5 h-5 mr-2 text-orange-500" />
                  Información del Pedido
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div class="space-y-4">
                    <div class="flex items-center space-x-3">
                      <Calendar class="w-5 h-5 text-orange-500" />
                      <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Fecha del Pedido</p>
                        <p class="font-medium text-gray-900 dark:text-white">
                          {{ formatDate(pedido.fecha) }}
                        </p>
                      </div>
                    </div>
                    
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
                      <User class="w-5 h-5 text-orange-500" />
                      <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Usuario</p>
                        <p class="font-medium text-gray-900 dark:text-white">
                          {{ pedido.usuario?.nombre || 'Sin usuario' }}
                        </p>
                      </div>
                    </div>
                  </div>
                  
                  <div class="space-y-4">
                    <div class="flex items-center space-x-3">
                      <Package class="w-5 h-5 text-orange-500" />
                      <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Total Productos</p>
                        <p class="font-medium text-gray-900 dark:text-white">
                          {{ pedido.estadisticas?.total_productos || 0 }}
                        </p>
                      </div>
                    </div>
                    
                    <div class="flex items-center space-x-3">
                      <TrendingUp class="w-5 h-5 text-orange-500" />
                      <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Total Items</p>
                        <p class="font-medium text-gray-900 dark:text-white">
                          {{ pedido.estadisticas?.total_items || 0 }}
                        </p>
                      </div>
                    </div>
                    
                    <div class="flex items-center space-x-3">
                      <DollarSign class="w-5 h-5 text-orange-500" />
                      <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Subtotal</p>
                        <p class="font-medium text-gray-900 dark:text-white">
                          {{ formatCurrency(pedido.estadisticas?.total_pedido || 0) }}
                        </p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Products List -->
              <div class="restaurant-card p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                  <Package class="w-5 h-5 mr-2 text-orange-500" />
                  Productos del Pedido
                </h3>
                
                <div class="space-y-4">
                  <div
                    v-for="detalle in pedido.detalles"
                    :key="detalle.id"
                    class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg"
                  >
                    <div class="flex items-center space-x-4">
                      <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900/30 rounded-lg flex items-center justify-center">
                        <Package class="w-6 h-6 text-orange-600 dark:text-orange-400" />
                      </div>
                      <div>
                        <h4 class="font-medium text-gray-900 dark:text-white">
                          {{ detalle.producto?.nombre }}
                        </h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                          {{ detalle.producto?.categoria }}
                        </p>
                      </div>
                    </div>
                    
                    <div class="flex items-center space-x-6">
                      <div class="text-center">
                        <p class="text-sm text-gray-600 dark:text-gray-400">Cantidad</p>
                        <p class="font-medium text-gray-900 dark:text-white">{{ detalle.cantidad }}</p>
                      </div>
                      
                      <div class="text-center">
                        <p class="text-sm text-gray-600 dark:text-gray-400">Precio Unit.</p>
                        <p class="font-medium text-gray-900 dark:text-white">
                          {{ formatCurrency(detalle.precio_unitario) }}
                        </p>
                      </div>
                      
                      <div class="text-center">
                        <p class="text-sm text-gray-600 dark:text-gray-400">Total</p>
                        <p class="font-bold text-orange-600 dark:text-orange-400">
                          {{ formatCurrency(detalle.precio_total) }}
                        </p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Order Timeline -->
              <div class="restaurant-card p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                  <Clock class="w-5 h-5 mr-2 text-orange-500" />
                  Historial del Pedido
                </h3>
                
                <div class="space-y-4">
                  <div
                    v-for="(evento, index) in pedido.timeline"
                    :key="index"
                    class="flex items-start space-x-4"
                  >
                    <div class="w-3 h-3 rounded-full mt-2" :style="{ backgroundColor: evento.color }"></div>
                    <div class="flex-1">
                      <div class="flex items-center space-x-2">
                        <component :is="getEstadoIcon(evento.estado)" class="w-4 h-4" :style="{ color: evento.color }" />
                        <span class="font-medium text-gray-900 dark:text-white">{{ evento.estado }}</span>
                        <span class="text-sm text-gray-500">{{ formatDate(evento.fecha) }}</span>
                      </div>
                      <p v-if="evento.comentario" class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                        {{ evento.comentario }}
                      </p>
                      <p v-if="evento.usuario" class="text-xs text-gray-500 mt-1">
                        Por: {{ evento.usuario }}
                      </p>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Right Column - Summary & Actions -->
            <div class="space-y-6">
              <!-- Order Summary -->
              <div class="restaurant-card p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                  <DollarSign class="w-5 h-5 mr-2 text-orange-500" />
                  Resumen Financiero
                </h3>

                <div class="space-y-3">
                  <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Subtotal:</span>
                    <span class="font-medium">{{ formatCurrency(pedido.estadisticas?.total_pedido || 0) }}</span>
                  </div>
                  <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">IVA (13%):</span>
                    <span class="font-medium">{{ formatCurrency(pedido.estadisticas?.impuestos || 0) }}</span>
                  </div>
                  <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Descuentos:</span>
                    <span class="font-medium text-green-600">{{ formatCurrency(pedido.estadisticas?.descuentos || 0) }}</span>
                  </div>
                  <div class="border-t border-gray-200 dark:border-gray-700 pt-3">
                    <div class="flex justify-between text-lg font-bold">
                      <span>Total Final:</span>
                      <span class="text-orange-600 dark:text-orange-400">
                        {{ formatCurrency(pedido.estadisticas?.total_final || 0) }}
                      </span>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Quick Actions -->
              <div class="restaurant-card p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Acciones Rápidas</h3>
                <div class="space-y-3">
                  <button
                    @click="changeEstado(2)"
                    :disabled="pedido.estado_id === 2"
                    class="btn-restaurant-secondary w-full justify-center"
                  >
                    <AlertCircle class="w-4 h-4 mr-2" />
                    Marcar en Proceso
                  </button>
                  <button
                    @click="changeEstado(3)"
                    :disabled="pedido.estado_id === 3"
                    class="btn-restaurant-secondary w-full justify-center"
                  >
                    <CheckCircle class="w-4 h-4 mr-2" />
                    Marcar Completado
                  </button>
                  <button
                    @click="changeEstado(4)"
                    :disabled="pedido.estado_id === 4"
                    class="btn-restaurant-secondary w-full justify-center text-red-600 hover:text-red-700"
                  >
                    <XCircle class="w-4 h-4 mr-2" />
                    Cancelar Pedido
                  </button>
                </div>
              </div>

              <!-- Client Information -->
              <div v-if="pedido.cliente" class="restaurant-card p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                  <User class="w-5 h-5 mr-2 text-orange-500" />
                  Información del Cliente
                </h3>
                
                <div class="space-y-3">
                  <div class="flex items-center space-x-3">
                    <User class="w-4 h-4 text-orange-500" />
                    <span class="text-gray-900 dark:text-white">{{ pedido.cliente.nombre }}</span>
                  </div>
                  
                  <div v-if="pedido.cliente.correo_electronico" class="flex items-center space-x-3">
                    <Mail class="w-4 h-4 text-orange-500" />
                    <span class="text-gray-600 dark:text-gray-400">{{ pedido.cliente.correo_electronico }}</span>
                  </div>
                  
                  <div v-if="pedido.cliente.telefono" class="flex items-center space-x-3">
                    <Phone class="w-4 h-4 text-orange-500" />
                    <span class="text-gray-600 dark:text-gray-400">{{ pedido.cliente.telefono }}</span>
                  </div>
                </div>
              </div>

              <!-- Danger Zone -->
              <div class="restaurant-card p-6 border border-red-200 dark:border-red-800">
                <h3 class="text-lg font-semibold text-red-600 dark:text-red-400 mb-4">Zona de Peligro</h3>
                <button
                  @click="deletePedido"
                  class="btn-restaurant-secondary w-full justify-center text-red-600 hover:text-red-700 border-red-300 hover:border-red-400"
                >
                  <Trash2 class="w-4 h-4 mr-2" />
                  Eliminar Pedido
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
