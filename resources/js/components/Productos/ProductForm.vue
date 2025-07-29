<template>
  <form @submit.prevent="handleSubmit" class="space-y-6">
    <!-- Product Image Upload -->
    <div>
      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
        Imagen del Producto
      </label>
      <ImageUpload
        v-model="form.imagen_url"
        :alt="form.nombre || 'Imagen del producto'"
        @file-change="handleImageChange"
      />
    </div>

    <!-- Product Name -->
    <div>
      <label for="nombre" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
        Nombre del Producto *
      </label>
      <input
        id="nombre"
        v-model="form.nombre"
        type="text"
        required
        maxlength="80"
        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
        :class="{ 'border-red-500': errors?.nombre }"
        placeholder="Ej: Hamburguesa Clásica"
      />
      <p v-if="errors?.nombre" class="mt-1 text-sm text-red-600">
        {{ errors.nombre }}
      </p>
    </div>

    <!-- Product Description -->
    <div>
      <label for="descripcion" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
        Descripción
      </label>
      <textarea
        id="descripcion"
        v-model="form.descripcion"
        rows="3"
        maxlength="255"
        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
        :class="{ 'border-red-500': errors?.descripcion }"
        placeholder="Describe los ingredientes y características del producto..."
      ></textarea>
      <div class="flex justify-between mt-1">
        <p v-if="errors?.descripcion" class="text-sm text-red-600">
          {{ errors.descripcion }}
        </p>
        <p class="text-sm text-gray-500">
          {{ form.descripcion?.length || 0 }}/255
        </p>
      </div>
    </div>

    <!-- Category Selection -->
    <div>
      <label for="categoria_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
        Categoría *
      </label>
      <select
        id="categoria_id"
        v-model="form.categoria_id"
        required
        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
        :class="{ 'border-red-500': errors?.categoria_id }"
      >
        <option value="">Selecciona una categoría</option>
        <option
          v-for="categoria in categorias"
          :key="categoria.id"
          :value="categoria.id"
        >
          {{ categoria.nombre }}
        </option>
      </select>
      <p v-if="errors?.categoria_id" class="mt-1 text-sm text-red-600">
        {{ errors.categoria_id }}
      </p>
    </div>

    <!-- Price -->
    <div>
      <label for="precio" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
        Precio *
      </label>
      <div class="relative">
        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">$</span>
        <input
          id="precio"
          v-model="form.precio"
          type="number"
          step="0.01"
          min="0"
          required
          class="w-full pl-8 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
          :class="{ 'border-red-500': errors?.precio }"
          placeholder="0.00"
        />
      </div>
      <p v-if="errors?.precio" class="mt-1 text-sm text-red-600">
        {{ errors.precio }}
      </p>
    </div>

    <!-- Submit Buttons -->
    <div class="flex space-x-3 pt-6">
      <button
        type="submit"
        :disabled="isSubmitting"
        class="flex-1 bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center"
      >
        <Loader v-if="isSubmitting" class="w-4 h-4 mr-2 animate-spin" />
        {{ isSubmitting ? 'Guardando...' : (producto ? 'Actualizar Producto' : 'Crear Producto') }}
      </button>
      <button
        type="button"
        @click="$emit('cancel')"
        class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200"
      >
        Cancelar
      </button>
    </div>
  </form>
</template>

<script setup lang="ts">
import { ref, reactive, computed, onMounted } from 'vue'
import { Loader } from 'lucide-vue-next'
import ImageUpload from './ImageUpload.vue'
import type { Producto, Categoria, ProductoFormData } from '@/types/productos'

interface Props {
  producto?: Producto
  categorias: Categoria[]
  errors?: Record<string, string>
}

const props = defineProps<Props>()

interface Emits {
  (e: 'submit', formData: FormData): void
  (e: 'cancel'): void
}

const emit = defineEmits<Emits>()

const isSubmitting = ref(false)
const selectedFile = ref<File | null>(null)

const form = reactive<ProductoFormData>({
  nombre: props.producto?.nombre || '',
  descripcion: props.producto?.descripcion || '',
  precio: Number(props.producto?.precio) || 0,
  categoria_id: Number(props.producto?.categoria_id) || 0,
  imagen_url: props.producto?.imagen_url || '',
})

const handleImageChange = (file: File | null) => {
  selectedFile.value = file
}

const handleSubmit = () => {
  isSubmitting.value = true
  
  const formData = new FormData()
  formData.append('nombre', form.nombre)
  formData.append('descripcion', form.descripcion || '')
  formData.append('precio', form.precio.toString())
  formData.append('categoria_id', form.categoria_id.toString())
  
  if (selectedFile.value) {
    formData.append('imagen', selectedFile.value)
  }
  
  emit('submit', formData)
  
  // Reset submitting state after a delay
  setTimeout(() => {
    isSubmitting.value = false
  }, 1000)
}
</script> 