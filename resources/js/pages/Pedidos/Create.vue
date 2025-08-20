<script setup lang="ts">
import { ref, onMounted, computed } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { 
  ArrowLeft,
  Plus,
  Minus,
  ShoppingCart,
  User,
  Calendar,
  Package,
  DollarSign,
  Save,
  X,
  Search,
  Filter
} from 'lucide-vue-next';
import { Link, router } from '@inertiajs/vue3';
import { pedidoService } from '@/services/pedidoService';
import { clientService } from '@/services/clientService';
import type { 
  PedidoFormData, 
  DetallePedidoForm, 
  ProductoPedido, 
  EstadoPedido
} from '@/types/pedidos';
import type { Cliente } from '@/types/client';

interface ProductoSeleccionado extends ProductoPedido {
  cantidad: number;
  precio_unitario: number;
  precio_total: number;
}

const pedidoForm = ref<PedidoFormData>({
  cliente_id: undefined,
  estado_id: 1, // Por defecto "Pendiente"
  fecha: new Date().toISOString().split('T')[0],
  detalles: []
});

const productos = ref<ProductoPedido[]>([]);
const clientes = ref<Cliente[]>([]);
const estados = ref<EstadoPedido[]>([]);
const isLoading = ref(false);
const showProductSelector = ref(false);
const searchProduct = ref('');
const selectedProducts = ref<ProductoSeleccionado[]>([]);

// Filtros de productos
const categoriaFilter = ref('');
const precioMinFilter = ref('');
const precioMaxFilter = ref('');

const fetchProductos = async () => {
  try {
    const response = await pedidoService.getProductosDisponibles();
    productos.value = response || [];
    console.log('Productos cargados:', productos.value);
    console.log('Primer producto:', productos.value[0]);
    if (productos.value[0]) {
      console.log('Tipo de precio:', typeof productos.value[0].precio);
      console.log('Valor de precio:', productos.value[0].precio);
    }
  } catch (error) {
    console.error('Error fetching products:', error);
    productos.value = [];
  }
};

const fetchClientes = async () => {
  try {
    // Usar el servicio de clientes
    const response = await clientService.getClients();
    clientes.value = response.data;
  } catch (error) {
    console.error('Error fetching clients:', error);
  }
};

const fetchEstados = async () => {
  try {
    const response = await pedidoService.getEstadosPedido();
    estados.value = response;
  } catch (error) {
    console.error('Error fetching states:', error);
  }
};

const productosFiltrados = computed(() => {
  if (!productos.value || !Array.isArray(productos.value)) {
    return [];
  }

  let filtered = productos.value;

  if (searchProduct.value) {
    filtered = filtered.filter(p => 
      p.nombre.toLowerCase().includes(searchProduct.value.toLowerCase()) ||
      p.categoria?.toLowerCase().includes(searchProduct.value.toLowerCase())
    );
  }

  if (categoriaFilter.value) {
    filtered = filtered.filter(p => p.categoria === categoriaFilter.value);
  }

  if (precioMinFilter.value) {
    filtered = filtered.filter(p => p.precio >= parseFloat(precioMinFilter.value));
  }

  if (precioMaxFilter.value) {
    filtered = filtered.filter(p => p.precio <= parseFloat(precioMaxFilter.value));
  }

  return filtered;
});

const categorias = computed(() => {
  if (!productos.value || !Array.isArray(productos.value)) {
    return [];
  }
  return [...new Set(productos.value.map(p => p.categoria).filter(Boolean))];
});

const agregarProducto = (producto: ProductoPedido) => {
  const existingProduct = selectedProducts.value.find(p => p.id === producto.id);
  
  if (existingProduct) {
    existingProduct.cantidad += 1;
    existingProduct.precio_total = existingProduct.cantidad * existingProduct.precio_unitario;
  } else {
    // Asegurar que el precio sea un número
    const precio = typeof producto.precio === 'string' ? parseFloat(producto.precio) : producto.precio;
    console.log('Agregando producto:', producto.nombre, 'precio:', precio, 'tipo:', typeof precio);
    
    selectedProducts.value.push({
      ...producto,
      cantidad: 1,
      precio_unitario: precio,
      precio_total: precio
    });
  }

  // Actualizar detalles del pedido
  actualizarDetallesPedido();
  showProductSelector.value = false;
};

const removerProducto = (index: number) => {
  selectedProducts.value.splice(index, 1);
  actualizarDetallesPedido();
};

const actualizarCantidad = (index: number, nuevaCantidad: number) => {
  if (nuevaCantidad > 0) {
    selectedProducts.value[index].cantidad = nuevaCantidad;
    selectedProducts.value[index].precio_total = nuevaCantidad * selectedProducts.value[index].precio_unitario;
    console.log('Actualizando cantidad:', nuevaCantidad, 'precio_total:', selectedProducts.value[index].precio_total);
    actualizarDetallesPedido();
  }
};

const actualizarPrecio = (index: number, nuevoPrecio: number) => {
  if (nuevoPrecio > 0) {
    selectedProducts.value[index].precio_unitario = nuevoPrecio;
    selectedProducts.value[index].precio_total = selectedProducts.value[index].cantidad * nuevoPrecio;
    console.log('Actualizando precio:', nuevoPrecio, 'precio_total:', selectedProducts.value[index].precio_total);
    actualizarDetallesPedido();
  }
};

const actualizarDetallesPedido = () => {
  pedidoForm.value.detalles = selectedProducts.value.map(p => ({
    producto_id: p.id,
    cantidad: p.cantidad,
    precio_unitario: p.precio_unitario,
    precio_total: p.precio_total
  }));
};

const totalPedido = computed(() => {
  const total = selectedProducts.value.reduce((total, p) => {
    console.log('Producto:', p.nombre, 'precio_total:', p.precio_total, 'tipo:', typeof p.precio_total);
    return total + p.precio_total;
  }, 0);
  console.log('Total calculado:', total);
  return total;
});

const totalItems = computed(() => {
  return selectedProducts.value.reduce((total, p) => total + p.cantidad, 0);
});

const isFormValid = computed(() => {
  return pedidoForm.value.cliente_id && 
         pedidoForm.value.estado_id && 
         pedidoForm.value.fecha && 
         selectedProducts.value.length > 0;
});

const submitPedido = async () => {
  if (!isFormValid.value) return;

  console.log('Enviando pedido con datos:', pedidoForm.value);
  console.log('Productos seleccionados:', selectedProducts.value);
  
  isLoading.value = true;
  try {
    const response = await pedidoService.createPedido(pedidoForm.value);
    console.log('Respuesta del servidor:', response);
    
    // Redirigir a la lista de pedidos con un parámetro para forzar actualización
    window.location.href = route('pedidos.index') + '?created=1';
  } catch (error: any) {
    console.error('Error creating order:', error);
    console.error('Detalles del error:', {
      message: error.message,
      response: error.response?.data,
      status: error.response?.status
    });
  } finally {
    isLoading.value = false;
  }
};

const formatCurrency = (amount: number) => {
  // Verificar si el valor es válido
  if (isNaN(amount) || !isFinite(amount)) {
    console.warn('Valor inválido para formatear:', amount);
    return 'Bs 0,00';
  }
  
  return new Intl.NumberFormat('es-BO', {
    style: 'currency',
    currency: 'BOB'
  }).format(amount);
};

onMounted(async () => {
  await Promise.all([
    fetchProductos(),
    fetchClientes(),
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
            <div class="flex items-center space-x-4">
              <Link :href="route('pedidos.index')">
                <button class="btn-restaurant-secondary inline-flex items-center">
                  <ArrowLeft class="w-5 h-5 mr-2" />
                  Volver
                </button>
              </Link>
              <div>
                <h1 class="text-4xl font-bold text-gradient mb-2">
                  🛒 Nuevo Pedido
                </h1>
                <p class="text-lg text-gray-600 dark:text-gray-400">
                  Crea un nuevo pedido para tu restaurante
                </p>
              </div>
            </div>
            <button
              @click="submitPedido"
              :disabled="!isFormValid || isLoading"
              class="btn-restaurant inline-flex items-center"
            >
              <Save class="w-5 h-5 mr-2" />
              {{ isLoading ? 'Creando...' : 'Crear Pedido' }}
            </button>
          </div>
        </div>
      </div>

      <!-- Content Section -->
      <div class="max-w-7xl mx-auto px-6 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
          <!-- Left Column - Order Details -->
          <div class="lg:col-span-2 space-y-6">
            <!-- Order Information -->
            <div class="restaurant-card p-6">
              <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                <ShoppingCart class="w-5 h-5 mr-2 text-orange-500" />
                Información del Pedido
              </h3>
              
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Cliente
                  </label>
                  <select
                    v-model="pedidoForm.cliente_id"
                    class="input-restaurant w-full"
                    required
                  >
                    <option value="">Seleccionar cliente</option>
                    <option
                      v-for="cliente in clientes"
                      :key="cliente.id"
                      :value="cliente.id"
                    >
                      {{ cliente.nombre }}
                    </option>
                  </select>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Estado
                  </label>
                  <select
                    v-model="pedidoForm.estado_id"
                    class="input-restaurant w-full"
                    required
                  >
                    <option
                      v-for="estado in estados"
                      :key="estado.id"
                      :value="estado.id"
                    >
                      {{ estado.nombre }}
                    </option>
                  </select>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Fecha
                  </label>
                  <input
                    v-model="pedidoForm.fecha"
                    type="date"
                    class="input-restaurant w-full"
                    required
                  />
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Total Items
                  </label>
                  <div class="input-restaurant w-full bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white">
                    {{ totalItems }} productos
                  </div>
                </div>
              </div>
            </div>

            <!-- Product Selection -->
            <div class="restaurant-card p-6">
              <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                  <Package class="w-5 h-5 mr-2 text-orange-500" />
                  Productos del Pedido
                </h3>
                <button
                  @click="showProductSelector = true"
                  class="btn-restaurant-secondary inline-flex items-center"
                >
                  <Plus class="w-4 h-4 mr-2" />
                  Agregar Producto
                </button>
              </div>

              <!-- Selected Products -->
              <div v-if="selectedProducts.length > 0" class="space-y-3">
                <div
                  v-for="(producto, index) in selectedProducts"
                  :key="producto.id"
                  class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg"
                >
                  <div class="flex items-center space-x-4 flex-1">
                    <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900/30 rounded-lg flex items-center justify-center">
                      <Package class="w-6 h-6 text-orange-600 dark:text-orange-400" />
                    </div>
                    <div class="flex-1">
                      <h4 class="font-medium text-gray-900 dark:text-white">{{ producto.nombre }}</h4>
                      <p class="text-sm text-gray-600 dark:text-gray-400">{{ producto.categoria }}</p>
                    </div>
                  </div>

                  <div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-2">
                      <button
                        @click="actualizarCantidad(index, producto.cantidad - 1)"
                        class="w-8 h-8 bg-gray-200 dark:bg-gray-600 rounded-full flex items-center justify-center hover:bg-gray-300 dark:hover:bg-gray-500"
                      >
                        <Minus class="w-4 h-4" />
                      </button>
                      <span class="w-12 text-center font-medium">{{ producto.cantidad }}</span>
                      <button
                        @click="actualizarCantidad(index, producto.cantidad + 1)"
                        class="w-8 h-8 bg-orange-200 dark:bg-orange-600 rounded-full flex items-center justify-center hover:bg-orange-300 dark:hover:bg-orange-500"
                      >
                        <Plus class="w-4 h-4" />
                      </button>
                    </div>

                    <div class="w-24">
                      <input
                        v-model.number="producto.precio_unitario"
                        @input="actualizarPrecio(index, producto.precio_unitario)"
                        type="number"
                        step="0.01"
                        min="0"
                        class="input-restaurant w-full text-center"
                      />
                    </div>

                    <div class="w-24 text-right font-medium text-gray-900 dark:text-white">
                      {{ formatCurrency(producto.precio_total) }}
                    </div>

                    <button
                      @click="removerProducto(index)"
                      class="w-8 h-8 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center hover:bg-red-200 dark:hover:bg-red-800 text-red-600"
                    >
                      <X class="w-4 h-4" />
                    </button>
                  </div>
                </div>
              </div>

              <!-- Empty State -->
              <div v-else class="text-center py-8">
                <Package class="w-16 h-16 mx-auto mb-4 text-gray-400" />
                <p class="text-gray-500 dark:text-gray-400">No hay productos seleccionados</p>
                <p class="text-sm text-gray-400 dark:text-gray-500">Haz clic en "Agregar Producto" para comenzar</p>
              </div>
            </div>
          </div>

          <!-- Right Column - Summary & Actions -->
          <div class="space-y-6">
            <!-- Order Summary -->
            <div class="restaurant-card p-6">
              <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                <DollarSign class="w-5 h-5 mr-2 text-orange-500" />
                Resumen del Pedido
              </h3>

              <div class="space-y-3">
                <div class="flex justify-between">
                  <span class="text-gray-600 dark:text-gray-400">Total Items:</span>
                  <span class="font-medium">{{ totalItems }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-600 dark:text-gray-400">Subtotal:</span>
                  <span class="font-medium">{{ formatCurrency(totalPedido) }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-600 dark:text-gray-400">IVA (13%):</span>
                  <span class="font-medium">{{ formatCurrency(totalPedido * 0.13) }}</span>
                </div>
                <div class="border-t border-gray-200 dark:border-gray-700 pt-3">
                  <div class="flex justify-between text-lg font-bold">
                    <span>Total:</span>
                    <span class="text-orange-600 dark:text-orange-400">
                      {{ formatCurrency(totalPedido * 1.13) }}
                    </span>
                  </div>
                </div>
              </div>

              <button
                @click="submitPedido"
                :disabled="!isFormValid || isLoading"
                class="btn-restaurant w-full mt-6"
              >
                <Save class="w-5 h-5 mr-2" />
                {{ isLoading ? 'Creando Pedido...' : 'Crear Pedido' }}
              </button>
            </div>

            <!-- Quick Actions -->
            <div class="restaurant-card p-6">
              <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Acciones Rápidas</h3>
              <div class="space-y-3">
                <button
                  @click="showProductSelector = true"
                  class="btn-restaurant-secondary w-full justify-center"
                >
                  <Plus class="w-4 h-4 mr-2" />
                  Agregar Productos
                </button>
                <button
                  @click="pedidoForm.fecha = new Date().toISOString().split('T')[0]"
                  class="btn-restaurant-secondary w-full justify-center"
                >
                  <Calendar class="w-4 h-4 mr-2" />
                  Fecha Actual
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Product Selector Modal -->
    <div v-if="showProductSelector" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl max-w-4xl w-full mx-4 max-h-[90vh] overflow-hidden">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
          <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
              Seleccionar Productos
            </h3>
            <button
              @click="showProductSelector = false"
              class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
            >
              <X class="w-6 h-6" />
            </button>
          </div>
        </div>

        <div class="p-6">
          <!-- Filters -->
          <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="relative">
              <input
                v-model="searchProduct"
                type="text"
                placeholder="Buscar productos..."
                class="input-restaurant w-full pl-10"
              />
              <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" />
            </div>

            <select
              v-model="categoriaFilter"
              class="input-restaurant"
            >
              <option value="">Todas las categorías</option>
              <option
                v-for="categoria in categorias"
                :key="categoria"
                :value="categoria"
              >
                {{ categoria }}
              </option>
            </select>

            <input
              v-model="precioMinFilter"
              type="number"
              placeholder="Precio mínimo"
              class="input-restaurant"
            />

            <input
              v-model="precioMaxFilter"
              type="number"
              placeholder="Precio máximo"
              class="input-restaurant"
            />
          </div>

          <!-- Products Grid -->
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 max-h-96 overflow-y-auto">
            <div
              v-for="producto in productosFiltrados"
              :key="producto.id"
              class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:border-orange-300 dark:hover:border-orange-600 transition-colors cursor-pointer"
              @click="agregarProducto(producto)"
            >
              <div class="flex items-center space-x-3">
                <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900/30 rounded-lg flex items-center justify-center">
                  <Package class="w-6 h-6 text-orange-600 dark:text-orange-400" />
                </div>
                <div class="flex-1">
                  <h4 class="font-medium text-gray-900 dark:text-white">{{ producto.nombre }}</h4>
                  <p class="text-sm text-gray-600 dark:text-gray-400">{{ producto.categoria }}</p>
                  <p class="text-lg font-bold text-orange-600 dark:text-orange-400">
                    {{ formatCurrency(producto.precio) }}
                  </p>
                </div>
                <Plus class="w-5 h-5 text-orange-500" />
              </div>
            </div>
          </div>

          <div class="mt-6 flex justify-end space-x-3">
            <button
              @click="showProductSelector = false"
              class="btn-restaurant-secondary"
            >
              Cerrar
            </button>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
