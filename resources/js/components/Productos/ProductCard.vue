<template>
  <div class="restaurant-card group animate-fade-in-up">
    <!-- Image Container -->
    <div class="food-image-container aspect-square">
      <img
        v-if="producto.imagen_url"
        :src="producto.imagen_url"
        :alt="producto.nombre"
        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
        @error="handleImageError"
      />
      <div
        v-else
        class="w-full h-full flex items-center justify-center"
      >
        <div class="text-center">
          <Package class="w-16 h-16 text-orange-400 mx-auto mb-2" />
          <p class="text-sm text-orange-600 dark:text-orange-400 font-medium">Sin imagen</p>
        </div>
      </div>
      
      <!-- Category Badge -->
      <div class="absolute top-4 left-4">
        <span class="food-badge">
          {{ producto.categoria?.nombre }}
        </span>
      </div>

      <!-- Action Buttons -->
      <div class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-all duration-300">
        <div class="flex space-x-2">
          <button
            @click="$emit('edit', producto)"
            class="p-2 bg-white/90 dark:bg-gray-800/90 backdrop-blur-sm rounded-lg shadow-lg hover:bg-white dark:hover:bg-gray-800 transition-all duration-200 transform hover:scale-110"
            title="Editar"
          >
            <Edit class="w-4 h-4 text-orange-600 dark:text-orange-400" />
          </button>
          <button
            @click="$emit('delete', producto)"
            class="p-2 bg-white/90 dark:bg-gray-800/90 backdrop-blur-sm rounded-lg shadow-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-all duration-200 transform hover:scale-110"
            title="Eliminar"
          >
            <Trash class="w-4 h-4 text-red-500" />
          </button>
        </div>
      </div>

      <!-- Hot Badge for Popular Items -->
      <div v-if="producto.precio > 10" class="absolute bottom-4 left-4">
        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-bold bg-gradient-to-r from-red-500 to-red-600 text-white shadow-lg">
          🔥 Popular
        </span>
      </div>
    </div>

    <!-- Content -->
    <div class="p-6">
      <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3 line-clamp-2 group-hover:text-orange-600 dark:group-hover:text-orange-400 transition-colors duration-200">
        {{ producto.nombre }}
      </h3>
      
      <p
        v-if="producto.descripcion"
        class="text-sm text-gray-600 dark:text-gray-400 mb-4 line-clamp-2"
      >
        {{ producto.descripcion }}
      </p>

      <!-- Price and Action -->
      <div class="flex items-center justify-between">
        <div class="flex items-baseline space-x-1">
          <span class="price-display">
            ${{ formatPrice(producto.precio) }}
          </span>
        </div>
        
        <Link
          :href="route('productos.show', producto.id)"
          class="btn-restaurant-secondary text-sm py-2 px-4 inline-flex items-center"
        >
          Ver detalles
          <ArrowRight class="w-4 h-4 ml-1" />
        </Link>
      </div>

      <!-- Additional Info -->
      <div class="mt-4 pt-4 border-t border-orange-200 dark:border-orange-800">
        <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
          <span>ID: #{{ producto.id }}</span>
          <span v-if="producto.created_at">
            {{ formatDate(producto.created_at) }}
          </span>
        </div>
      </div>
    </div>

    <!-- Hover Overlay -->
    <div class="absolute inset-0 bg-gradient-to-t from-orange-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none rounded-2xl" />
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3'
import { Package, Edit, Trash, ArrowRight } from 'lucide-vue-next'
import type { Producto } from '@/types/productos'

interface Props {
  producto: Producto
}

interface Emits {
  (e: 'edit', producto: Producto): void
  (e: 'delete', producto: Producto): void
}

defineProps<Props>()
defineEmits<Emits>()

const imageError = ref(false)

const handleImageError = () => {
  imageError.value = true
}

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

const formatDate = (dateString: string): string => {
  const date = new Date(dateString)
  return date.toLocaleDateString('es-ES', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric'
  })
}
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style> 