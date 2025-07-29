<template>
  <AppLayout :breadcrumbs="breadcrumbs">
    <Head title="Productos - Pandaburger" />
    
    <div class="min-h-screen bg-gradient-to-br from-orange-50 to-orange-100 dark:from-gray-900 dark:to-orange-900/10">
      <!-- Header Section -->
      <div class="bg-white dark:bg-gray-800 shadow-lg border-b border-orange-200 dark:border-orange-800">
        <div class="max-w-7xl mx-auto px-6 py-8">
          <div class="flex items-center justify-between">
            <div>
              <h1 class="text-4xl font-bold text-gradient mb-2">
                🍔 Productos
              </h1>
              <p class="text-lg text-gray-600 dark:text-gray-400">
                Gestiona el catálogo de productos de Pandaburger
              </p>
            </div>
            <Link
              :href="route('productos.create')"
              class="btn-restaurant inline-flex items-center"
            >
              <Plus class="w-5 h-5 mr-2" />
              Nuevo Producto
            </Link>
          </div>
        </div>
      </div>

      <!-- Content Section -->
      <div class="max-w-7xl mx-auto px-6 py-8">
        <!-- Filters and Controls -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-orange-200 dark:border-orange-800 p-6 mb-8">
          <div class="flex flex-col sm:flex-row items-center justify-between space-y-4 sm:space-y-0 sm:space-x-4">
            <!-- Search and Filters -->
            <div class="flex items-center space-x-4 flex-1">
              <div class="relative flex-1 max-w-md">
                <input
                  v-model="search"
                  type="text"
                  placeholder="Buscar productos..."
                  class="input-restaurant w-full"
                />
                <Search class="absolute right-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" />
              </div>
              
              <select
                v-model="selectedCategory"
                class="input-restaurant max-w-xs"
              >
                <option value="">Todas las categorías</option>
                <option
                  v-for="categoria in categorias"
                  :key="categoria.id"
                  :value="categoria.id"
                >
                  {{ categoria.nombre }}
                </option>
              </select>
            </div>

            <!-- View Toggle and Results -->
            <div class="flex items-center space-x-4">
              <span class="text-sm text-gray-600 dark:text-gray-400">
                {{ filteredProductos.length }} productos encontrados
              </span>
              
              <div class="flex items-center bg-gray-100 dark:bg-gray-700 rounded-lg p-1">
                <button
                  @click="viewMode = 'grid'"
                  :class="[
                    'p-2 rounded-md transition-colors',
                    viewMode === 'grid' 
                      ? 'bg-white dark:bg-gray-600 text-orange-600 dark:text-orange-400 shadow-sm' 
                      : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300'
                  ]"
                >
                  <Grid class="w-4 h-4" />
                </button>
                <button
                  @click="viewMode = 'list'"
                  :class="[
                    'p-2 rounded-md transition-colors',
                    viewMode === 'list' 
                      ? 'bg-white dark:bg-gray-600 text-orange-600 dark:text-orange-400 shadow-sm' 
                      : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300'
                  ]"
                >
                  <List class="w-4 h-4" />
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Products Grid/List -->
        <div v-if="filteredProductos.length > 0">
          <!-- Grid View -->
          <div
            v-if="viewMode === 'grid'"
            class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6"
          >
            <ProductCard
              v-for="producto in paginatedProductos"
              :key="producto.id"
              :producto="producto"
              @delete="confirmDelete"
            />
          </div>

          <!-- List View -->
          <div
            v-else
            class="space-y-4"
          >
            <div
              v-for="producto in paginatedProductos"
              :key="producto.id"
              class="restaurant-card p-6"
            >
              <div class="flex items-center space-x-6">
                <!-- Product Image -->
                <div class="food-image-container w-24 h-24 flex-shrink-0">
                  <img
                    v-if="producto.imagen_url"
                    :src="producto.imagen_url"
                    :alt="producto.nombre"
                    class="w-full h-full object-cover rounded-lg"
                  />
                  <div
                    v-else
                    class="w-full h-full bg-orange-100 dark:bg-orange-900/30 rounded-lg flex items-center justify-center"
                  >
                    <Package class="w-8 h-8 text-orange-400" />
                  </div>
                </div>

                <!-- Product Info -->
                <div class="flex-1">
                  <div class="flex items-start justify-between">
                    <div>
                      <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
                        {{ producto.nombre }}
                      </h3>
                      <p class="text-gray-600 dark:text-gray-400 mb-3">
                        {{ producto.descripcion || 'Sin descripción' }}
                      </p>
                      <div class="flex items-center space-x-4">
                        <span class="food-badge">
                          {{ producto.categoria?.nombre }}
                        </span>
                        <span class="price-display text-xl">
                          ${{ formatPrice(producto.precio) }}
                        </span>
                      </div>
                    </div>
                    
                    <div class="flex items-center space-x-2">
                      <Link
                        :href="route('productos.show', producto.id)"
                        class="btn-restaurant-secondary"
                      >
                        Ver detalles
                      </Link>
                      <Link
                        :href="route('productos.edit', producto.id)"
                        class="btn-restaurant"
                      >
                        Editar
                      </Link>
                      <button
                        @click="confirmDelete(producto)"
                        class="btn-restaurant bg-red-600 hover:bg-red-700"
                      >
                        Eliminar
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Pagination -->
          <div class="mt-8 flex justify-center">
            <div class="flex items-center space-x-2">
              <button
                @click="currentPage = Math.max(1, currentPage - 1)"
                :disabled="currentPage === 1"
                class="btn-restaurant-secondary disabled:opacity-50 disabled:cursor-not-allowed"
              >
                Anterior
              </button>
              
              <div class="flex items-center space-x-1">
                <button
                  v-for="page in visiblePages"
                  :key="page"
                  @click="currentPage = page"
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
                @click="currentPage = Math.min(totalPages, currentPage + 1)"
                :disabled="currentPage === totalPages"
                class="btn-restaurant-secondary disabled:opacity-50 disabled:cursor-not-allowed"
              >
                Siguiente
              </button>
            </div>
          </div>
        </div>

        <!-- Empty State -->
        <div
          v-else
          class="text-center py-12"
        >
          <div class="w-24 h-24 bg-orange-100 dark:bg-orange-900/30 rounded-full flex items-center justify-center mx-auto mb-6">
            <Package class="w-12 h-12 text-orange-400" />
          </div>
          <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">
            No se encontraron productos
          </h3>
          <p class="text-gray-600 dark:text-gray-400 mb-6">
            No hay productos que coincidan con tu búsqueda.
          </p>
          <Link
            :href="route('productos.create')"
            class="btn-restaurant inline-flex items-center"
          >
            <Plus class="w-5 h-5 mr-2" />
            Crear Primer Producto
          </Link>
        </div>
      </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div
      v-if="showDeleteModal"
      class="fixed inset-0 bg-black/50 flex items-center justify-center z-50"
      @click="showDeleteModal = false"
    >
      <div
        class="bg-white dark:bg-gray-800 rounded-2xl p-6 max-w-md w-full mx-4 shadow-2xl"
        @click.stop
      >
        <div class="flex items-center space-x-3 mb-4">
          <div class="w-12 h-12 bg-red-100 dark:bg-red-900/20 rounded-full flex items-center justify-center">
            <Trash class="w-6 h-6 text-red-500" />
          </div>
          <div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
              Eliminar Producto
            </h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">
              Esta acción no se puede deshacer
            </p>
          </div>
        </div>
        
        <p class="text-gray-700 dark:text-gray-300 mb-6">
          ¿Estás seguro de que quieres eliminar <strong>{{ productoToDelete?.nombre }}</strong>?
        </p>
        
        <div class="flex space-x-3">
          <button
            @click="showDeleteModal = false"
            class="btn-restaurant-secondary flex-1"
          >
            Cancelar
          </button>
          <button
            @click="deleteProduct"
            class="btn-restaurant flex-1 bg-red-600 hover:bg-red-700"
          >
            Eliminar
          </button>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, watch } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import {
  Plus,
  Search,
  Grid,
  List,
  Trash,
  Package,
  Edit
} from 'lucide-vue-next'
import ProductCard from '@/components/Productos/ProductCard.vue'
import type { Producto, Categoria } from '@/types/productos'
import AppLayout from '@/layouts/AppLayout.vue'
import type { BreadcrumbItem } from '@/types'

interface Props {
  productos: Producto[]
  categorias: Categoria[]
}

const props = defineProps<Props>()

const search = ref('')
const selectedCategory = ref('')
const viewMode = ref<'grid' | 'list'>('grid')
const currentPage = ref(1)
const itemsPerPage = 12
const showDeleteModal = ref(false)
const productoToDelete = ref<Producto | null>(null)

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Dashboard',
    href: '/dashboard',
  },
  {
    title: 'Productos',
    href: '/productos',
  },
]

const filteredProductos = computed(() => {
  let filtered = props.productos

  // Filter by search
  if (search.value) {
    const searchLower = search.value.toLowerCase()
    filtered = filtered.filter(producto =>
      producto.nombre.toLowerCase().includes(searchLower) ||
      (producto.descripcion && producto.descripcion.toLowerCase().includes(searchLower))
    )
  }

  // Filter by category
  if (selectedCategory.value) {
    filtered = filtered.filter(producto => 
      producto.categoria_id === parseInt(selectedCategory.value)
    )
  }

  return filtered
})

const totalPages = computed(() => Math.ceil(filteredProductos.value.length / itemsPerPage))

const paginatedProductos = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage
  const end = start + itemsPerPage
  return filteredProductos.value.slice(start, end)
})

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

const formatPrice = (price: any): string => {
  if (price === null || price === undefined || price === '') {
    return '0.00'
  }
  
  const numPrice = parseFloat(price)
  
  if (isNaN(numPrice)) {
    return '0.00'
  }
  
  return numPrice.toFixed(2)
}

const confirmDelete = (producto: Producto) => {
  productoToDelete.value = producto
  showDeleteModal.value = true
}

const deleteProduct = () => {
  if (productoToDelete.value) {
    router.delete(route('productos.destroy', productoToDelete.value.id), {
      onSuccess: () => {
        showDeleteModal.value = false
        productoToDelete.value = null
      },
    })
  }
}

// Reset to first page when filters change
watch([search, selectedCategory], () => {
  currentPage.value = 1
})

onMounted(() => {
  // Any initialization if needed
})
</script> 