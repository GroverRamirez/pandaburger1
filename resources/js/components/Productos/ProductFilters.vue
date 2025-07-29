<template>
  <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
    <div class="flex items-center justify-between mb-6">
      <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
        Filtros de Productos
      </h3>
      <button
        @click="clearFilters"
        class="text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 transition-colors"
      >
        Limpiar filtros
      </button>
    </div>

    <form @submit.prevent="applyFilters" class="space-y-6">
      <!-- Search -->
      <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
          Buscar productos
        </label>
        <div class="relative">
          <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" />
          <input
            v-model="filters.search"
            type="text"
            placeholder="Buscar por nombre o descripción..."
            class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
          />
        </div>
      </div>

      <!-- Category Filter -->
      <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
          Categoría
        </label>
        <select
          v-model="filters.categoria_id"
          class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
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

      <!-- Price Range -->
      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Precio mínimo
          </label>
          <input
            v-model.number="filters.precio_min"
            type="number"
            step="0.01"
            min="0"
            placeholder="0.00"
            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
          />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Precio máximo
          </label>
          <input
            v-model.number="filters.precio_max"
            type="number"
            step="0.01"
            min="0"
            placeholder="999.99"
            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
          />
        </div>
      </div>

      <!-- Sort Options -->
      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Ordenar por
          </label>
          <select
            v-model="filters.sort_by"
            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
          >
            <option value="created_at">Fecha de creación</option>
            <option value="nombre">Nombre</option>
            <option value="precio">Precio</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Orden
          </label>
          <select
            v-model="filters.sort_order"
            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
          >
            <option value="desc">Descendente</option>
            <option value="asc">Ascendente</option>
          </select>
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="flex space-x-3 pt-4">
        <button
          type="submit"
          class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200"
        >
          <Filter class="w-4 h-4 mr-2" />
          Aplicar Filtros
        </button>
        <button
          type="button"
          @click="clearFilters"
          class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200"
        >
          Limpiar
        </button>
      </div>
    </form>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, watch } from 'vue'
import { Search, Filter } from 'lucide-vue-next'
import type { Categoria, ProductosFilters } from '@/types/productos'

interface Props {
  categorias: Categoria[]
  initialFilters?: ProductosFilters
}

interface Emits {
  (e: 'filters-changed', filters: ProductosFilters): void
}

const props = defineProps<Props>()
const emit = defineEmits<Emits>()

const filters = reactive<ProductosFilters>({
  search: props.initialFilters?.search || '',
  categoria_id: props.initialFilters?.categoria_id || undefined,
  precio_min: props.initialFilters?.precio_min || undefined,
  precio_max: props.initialFilters?.precio_max || undefined,
  sort_by: props.initialFilters?.sort_by || 'created_at',
  sort_order: props.initialFilters?.sort_order || 'desc',
})

const applyFilters = () => {
  emit('filters-changed', { ...filters })
}

const clearFilters = () => {
  Object.assign(filters, {
    search: '',
    categoria_id: undefined,
    precio_min: undefined,
    precio_max: undefined,
    sort_by: 'created_at',
    sort_order: 'desc',
  })
  emit('filters-changed', { ...filters })
}

// Auto-apply filters when they change
watch(filters, () => {
  applyFilters()
}, { deep: true })
</script> 