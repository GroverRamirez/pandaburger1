<template>
  <AppLayout :breadcrumbs="breadcrumbs">
    <Head title="Detalles del Producto" />

    <div class="min-h-screen bg-gradient-to-br from-orange-50 to-orange-100 dark:from-gray-900 dark:to-orange-900/10">
      <!-- Header Section -->
      <div class="bg-white dark:bg-gray-800 shadow-lg border-b border-orange-200 dark:border-orange-800">
        <div class="max-w-7xl mx-auto px-6 py-8">
          <div class="flex items-center justify-between">
            <div>
              <h1 class="text-4xl font-bold text-gradient mb-2">
                🍔 {{ producto.nombre }}
              </h1>
              <p class="text-lg text-gray-600 dark:text-gray-400">
                Detalles del producto
              </p>
            </div>
            <div class="flex space-x-3">
              <Link
                :href="route('productos.edit', producto.id)"
                class="btn-restaurant-secondary inline-flex items-center"
              >
                <Edit class="w-5 h-5 mr-2" />
                Editar Producto
              </Link>
              <button
                @click="deleteProduct"
                class="bg-red-600 hover:bg-red-700 text-white font-semibold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 inline-flex items-center"
              >
                <Trash class="w-5 h-5 mr-2" />
                Eliminar
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Content Section -->
      <div class="max-w-7xl mx-auto px-6 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
          <!-- Product Image -->
          <div class="restaurant-card p-6">
            <div class="food-image-container aspect-square">
              <img
                v-if="producto.imagen_url"
                :src="producto.imagen_url"
                :alt="producto.nombre"
                class="w-full h-full object-cover rounded-xl"
              />
              <div
                v-else
                class="w-full h-full flex items-center justify-center bg-orange-100 dark:bg-orange-900/30 rounded-xl"
              >
                <div class="text-center">
                  <Package class="w-24 h-24 text-orange-400 mx-auto mb-4" />
                  <p class="text-lg text-orange-600 dark:text-orange-400 font-medium">Sin imagen</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Product Details -->
          <div class="space-y-6">
            <!-- Basic Info -->
            <div class="restaurant-card p-6">
              <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
                Información del Producto
              </h2>
              
              <div class="space-y-4">
                <div class="flex items-center justify-between">
                  <span class="text-gray-600 dark:text-gray-400 font-medium">Nombre:</span>
                  <span class="text-gray-900 dark:text-white font-semibold">{{ producto.nombre }}</span>
                </div>
                
                <div class="flex items-center justify-between">
                  <span class="text-gray-600 dark:text-gray-400 font-medium">Categoría:</span>
                  <span class="food-badge">{{ producto.categoria?.nombre }}</span>
                </div>
                
                <div class="flex items-center justify-between">
                  <span class="text-gray-600 dark:text-gray-400 font-medium">Precio:</span>
                  <span class="price-display text-3xl">{{ formatPrice(producto.precio) }}</span>
                </div>
                
                <div v-if="producto.descripcion" class="pt-4 border-t border-orange-200 dark:border-orange-800">
                  <span class="text-gray-600 dark:text-gray-400 font-medium block mb-2">Descripción:</span>
                  <p class="text-gray-700 dark:text-gray-300">{{ producto.descripcion }}</p>
                </div>
              </div>
            </div>

            <!-- Metadata -->
            <div class="restaurant-card p-6">
              <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                Información Técnica
              </h3>
              
              <div class="space-y-3">
                <div class="flex items-center justify-between">
                  <span class="text-gray-600 dark:text-gray-400">ID del Producto:</span>
                  <span class="text-gray-900 dark:text-white font-mono">#{{ producto.id }}</span>
                </div>
                
                <div class="flex items-center justify-between">
                  <span class="text-gray-600 dark:text-gray-400">Creado:</span>
                  <span class="text-gray-900 dark:text-white">{{ formatDate(producto.created_at) }}</span>
                </div>
                
                <div class="flex items-center justify-between">
                  <span class="text-gray-600 dark:text-gray-400">Actualizado:</span>
                  <span class="text-gray-900 dark:text-white">{{ formatDate(producto.updated_at) }}</span>
                </div>
              </div>
            </div>

            <!-- Actions -->
            <div class="restaurant-card p-6">
              <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                Acciones
              </h3>
              
              <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3">
                <Link
                  :href="route('productos.edit', producto.id)"
                  class="btn-restaurant flex-1 inline-flex items-center justify-center"
                >
                  <Edit class="w-5 h-5 mr-2" />
                  Editar Producto
                </Link>
                
                <Link
                  :href="route('productos.index')"
                  class="btn-restaurant-secondary flex-1 inline-flex items-center justify-center"
                >
                  <ArrowLeft class="w-5 h-5 mr-2" />
                  Volver a Productos
                </Link>
              </div>
            </div>
          </div>
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
          ¿Estás seguro de que quieres eliminar <strong>{{ producto.nombre }}</strong>?
        </p>
        
        <div class="flex space-x-3">
          <button
            @click="showDeleteModal = false"
            class="btn-restaurant-secondary flex-1"
          >
            Cancelar
          </button>
          <button
            @click="confirmDelete"
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
import { ref } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'

import { Edit, ArrowLeft, Package, Trash } from 'lucide-vue-next'
import AppLayout from '@/layouts/AppLayout.vue'
import type { BreadcrumbItem } from '@/types'
import type { Producto } from '@/types/productos'

interface Props {
  producto: Producto
}

const props = defineProps<Props>()

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Dashboard',
    href: '/dashboard',
  },
  {
    title: 'Productos',
    href: '/productos',
  },
  {
    title: props.producto.nombre,
    href: `/productos/${props.producto.id}`,
  },
]

const showDeleteModal = ref(false)

const deleteProduct = () => {
  showDeleteModal.value = true
}

const confirmDelete = () => {
  router.delete(route('productos.destroy', props.producto.id), {
    onSuccess: () => {
      showDeleteModal.value = false
    },
  })
}

const formatPrice = (price: any): string => {
  if (price === null || price === undefined || price === '') {
    return 'Bs 0.00'
  }
  
  const numPrice = parseFloat(price)
  
  if (isNaN(numPrice)) {
    return 'Bs 0.00'
  }
  
  return new Intl.NumberFormat('es-BO', {
    style: 'currency',
    currency: 'BOB'
  }).format(numPrice)
}

const formatDate = (date: string): string => {
  return new Date(date).toLocaleDateString('es-ES', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  })
}
</script> 