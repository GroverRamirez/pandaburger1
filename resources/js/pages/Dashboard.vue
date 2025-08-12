<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import type { BreadcrumbItem } from '@/types'
import {
  Package,
  CheckCircle,
  DollarSign,
  Clock,
  TrendingUp,
  AlertCircle,
  Plus,
  Users,
  Activity,
  Flame,
  UtensilsCrossed
} from 'lucide-vue-next'

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Dashboard',
    href: '/dashboard',
  },
]

const currentTime = ref('')
const currentDate = ref('')

const stats = ref({
  totalProductos: 24,
  productosActivos: 22,
  ventasMes: 12450,
  pedidosPendientes: 8
})

const formatPrice = (price: number): string => {
  return price.toLocaleString('es-BO', {
    style: 'currency',
    currency: 'BOB',
    minimumFractionDigits: 0,
    maximumFractionDigits: 0
  })
}

const updateDateTime = () => {
  const now = new Date()
  currentTime.value = now.toLocaleTimeString('es-ES', {
    hour: '2-digit',
    minute: '2-digit'
  })
  currentDate.value = now.toLocaleDateString('es-ES', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}

onMounted(() => {
  updateDateTime()
  setInterval(updateDateTime, 1000)
})
</script>

<template>
  <AppLayout :breadcrumbs="breadcrumbs">
    <Head title="Dashboard - Pandaburger" />
    
    <div class="min-h-screen bg-gradient-to-br from-orange-50 via-orange-100 to-orange-200 dark:from-gray-900 dark:via-orange-900/20 dark:to-orange-800/30">
      <!-- Header Section -->
      <div class="bg-gradient-to-r from-orange-500 to-orange-600 shadow-2xl border-b border-orange-400">
        <div class="max-w-7xl mx-auto px-6 py-12">
          <div class="text-center">
            <h1 class="text-5xl font-bold text-white mb-4 animate-fade-in-up">
              🍔 ¡Bienvenido a Pandaburger!
            </h1>
            <p class="text-xl text-orange-100 font-medium">
              Sistema de gestión para tu restaurante
            </p>
            <div class="mt-6 flex justify-center space-x-4">
              <div class="flex items-center space-x-2 bg-white/20 rounded-full px-4 py-2">
                <span class="text-orange-100">🕐</span>
                <span class="text-white font-medium">{{ currentTime }}</span>
              </div>
              <div class="flex items-center space-x-2 bg-white/20 rounded-full px-4 py-2">
                <span class="text-orange-100">📅</span>
                <span class="text-white font-medium">{{ currentDate }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Main Content -->
      <div class="max-w-7xl mx-auto px-6 py-8">
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
          <div class="restaurant-card group hover:scale-105 transition-all duration-300">
            <div class="p-6">
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-sm font-medium text-orange-600 dark:text-orange-400 mb-1">
                    Total Productos
                  </p>
                  <p class="text-3xl font-bold text-gray-900 dark:text-white">
                    {{ stats.totalProductos }}
                  </p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                  <Package class="w-6 h-6 text-white" />
                </div>
              </div>
              <div class="mt-4 flex items-center text-sm text-green-600 dark:text-green-400">
                <TrendingUp class="w-4 h-4 mr-1" />
                +12% este mes
              </div>
            </div>
          </div>

          <div class="restaurant-card group hover:scale-105 transition-all duration-300">
            <div class="p-6">
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-sm font-medium text-orange-600 dark:text-orange-400 mb-1">
                    Productos Activos
                  </p>
                  <p class="text-3xl font-bold text-gray-900 dark:text-white">
                    {{ stats.productosActivos }}
                  </p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg">
                  <CheckCircle class="w-6 h-6 text-white" />
                </div>
              </div>
              <div class="mt-4 flex items-center text-sm text-green-600 dark:text-green-400">
                <TrendingUp class="w-4 h-4 mr-1" />
                +8% este mes
              </div>
            </div>
          </div>

          <div class="restaurant-card group hover:scale-105 transition-all duration-300">
            <div class="p-6">
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-sm font-medium text-orange-600 dark:text-orange-400 mb-1">
                    Ventas del Mes
                  </p>
                  <p class="text-3xl font-bold text-gray-900 dark:text-white">
                    {{ formatPrice(stats.ventasMes) }}
                  </p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl flex items-center justify-center shadow-lg">
                  <DollarSign class="w-6 h-6 text-white" />
                </div>
              </div>
              <div class="mt-4 flex items-center text-sm text-green-600 dark:text-green-400">
                <TrendingUp class="w-4 h-4 mr-1" />
                +15% este mes
              </div>
            </div>
          </div>

          <div class="restaurant-card group hover:scale-105 transition-all duration-300">
            <div class="p-6">
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-sm font-medium text-orange-600 dark:text-orange-400 mb-1">
                    Pedidos Pendientes
                  </p>
                  <p class="text-3xl font-bold text-gray-900 dark:text-white">
                    {{ stats.pedidosPendientes }}
                  </p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl flex items-center justify-center shadow-lg">
                  <Clock class="w-6 h-6 text-white" />
                </div>
              </div>
              <div class="mt-4 flex items-center text-sm text-orange-600 dark:text-orange-400">
                <AlertCircle class="w-4 h-4 mr-1" />
                Requieren atención
              </div>
            </div>
          </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
          <Link
            :href="route('productos.create')"
            class="restaurant-card group hover:scale-105 transition-all duration-300 cursor-pointer"
          >
            <div class="p-6 text-center">
              <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg group-hover:shadow-xl transition-shadow">
                <Plus class="w-8 h-8 text-white" />
              </div>
              <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
                Crear Producto
              </h3>
              <p class="text-gray-600 dark:text-gray-400">
                Añadir un nuevo producto al catálogo
              </p>
            </div>
          </Link>

          <Link
            :href="route('productos.index')"
            class="restaurant-card group hover:scale-105 transition-all duration-300 cursor-pointer"
          >
            <div class="p-6 text-center">
              <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-green-600 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg group-hover:shadow-xl transition-shadow">
                <Package class="w-8 h-8 text-white" />
              </div>
              <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
                Ver Productos
              </h3>
              <p class="text-gray-600 dark:text-gray-400">
                Gestionar el catálogo de productos
              </p>
            </div>
          </Link>

          <Link
            href="/clientes"
            class="restaurant-card group hover:scale-105 transition-all duration-300 cursor-pointer"
          >
            <div class="p-6 text-center">
              <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg group-hover:shadow-xl transition-shadow">
                <Users class="w-8 h-8 text-white" />
              </div>
              <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
                Gestionar Clientes
              </h3>
              <p class="text-gray-600 dark:text-gray-400">
                Administrar la base de datos de clientes
              </p>
            </div>
          </Link>
        </div>

        <!-- Recent Activity & Popular Products -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
          <!-- Recent Activity -->
          <div class="restaurant-card">
            <div class="p-6">
              <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                  📋 Actividad Reciente
                </h2>
                <div class="w-8 h-8 bg-orange-100 dark:bg-orange-900/30 rounded-full flex items-center justify-center">
                  <Activity class="w-4 h-4 text-orange-600 dark:text-orange-400" />
                </div>
              </div>
              
              <div class="space-y-4">
                <div class="flex items-start space-x-3 p-4 bg-green-50 dark:bg-green-900/20 rounded-xl border border-green-200 dark:border-green-800">
                  <div class="w-3 h-3 bg-green-500 rounded-full mt-2"></div>
                  <div class="flex-1">
                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                      Producto 'Hamburguesa Clásica' actualizado
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                      Hace 2 horas
                    </p>
                  </div>
                </div>
                
                <div class="flex items-start space-x-3 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-xl border border-blue-200 dark:border-blue-800">
                  <div class="w-3 h-3 bg-blue-500 rounded-full mt-2"></div>
                  <div class="flex-1">
                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                      Nuevo producto 'Coca Cola' creado
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                      Hace 4 horas
                    </p>
                  </div>
                </div>
                
                <div class="flex items-start space-x-3 p-4 bg-orange-50 dark:bg-orange-900/20 rounded-xl border border-orange-200 dark:border-orange-800">
                  <div class="w-3 h-3 bg-orange-500 rounded-full mt-2"></div>
                  <div class="flex-1">
                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                      Categoría 'Bebidas' modificada
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                      Hace 1 día
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Popular Products -->
          <div class="restaurant-card">
            <div class="p-6">
              <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                  🔥 Productos Populares
                </h2>
                <div class="w-8 h-8 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center">
                  <Flame class="w-4 h-4 text-red-600 dark:text-red-400" />
                </div>
              </div>
              
              <div class="space-y-4">
                <div class="flex items-center space-x-4 p-4 bg-gradient-to-r from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20 rounded-xl border border-orange-200 dark:border-orange-800">
                  <div class="w-12 h-12 bg-orange-500 rounded-lg flex items-center justify-center">
                    <span class="text-white font-bold text-lg">🍔</span>
                  </div>
                  <div class="flex-1">
                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                      Hamburguesa Doble
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                      Bs 12.00 - 45 ventas
                    </p>
                  </div>
                  <div class="text-right">
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-bold bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                      🔥 Popular
                    </span>
                  </div>
                </div>
                
                <div class="flex items-center space-x-4 p-4 bg-gradient-to-r from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-xl border border-green-200 dark:border-green-800">
                  <div class="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center">
                    <span class="text-white font-bold text-lg">🥤</span>
                  </div>
                  <div class="flex-1">
                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                      Coca Cola
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                      Bs 2.50 - 32 ventas
                    </p>
                  </div>
                  <div class="text-right">
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                      📈 Trending
                    </span>
                  </div>
                </div>
                
                <div class="flex items-center space-x-4 p-4 bg-gradient-to-r from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-xl border border-blue-200 dark:border-blue-800">
                  <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center">
                    <span class="text-white font-bold text-lg">🍟</span>
                  </div>
                  <div class="flex-1">
                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                      Papas Fritas
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                      Bs 3.50 - 28 ventas
                    </p>
                  </div>
                  <div class="text-right">
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                      ⭐ Top
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Restaurant Stats -->
        <div class="mt-8">
          <div class="restaurant-card">
            <div class="p-6">
              <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">
                📊 Estadísticas del Restaurante
              </h2>
              
              <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center p-4 bg-gradient-to-br from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20 rounded-xl border border-orange-200 dark:border-orange-800">
                  <div class="w-12 h-12 bg-orange-500 rounded-full flex items-center justify-center mx-auto mb-3">
                    <UtensilsCrossed class="w-6 h-6 text-white" />
                  </div>
                  <p class="text-2xl font-bold text-gray-900 dark:text-white">156</p>
                  <p class="text-sm text-gray-600 dark:text-gray-400">Pedidos Hoy</p>
                </div>
                
                <div class="text-center p-4 bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-xl border border-green-200 dark:border-green-800">
                  <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-3">
                    <DollarSign class="w-6 h-6 text-white" />
                  </div>
                  <p class="text-2xl font-bold text-gray-900 dark:text-white">Bs 2,450</p>
                  <p class="text-sm text-gray-600 dark:text-gray-400">Ingresos Hoy</p>
                </div>
                
                <div class="text-center p-4 bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-xl border border-blue-200 dark:border-blue-800">
                  <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center mx-auto mb-3">
                    <Users class="w-6 h-6 text-white" />
                  </div>
                  <p class="text-2xl font-bold text-gray-900 dark:text-white">89</p>
                  <p class="text-sm text-gray-600 dark:text-gray-400">Clientes Nuevos</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
