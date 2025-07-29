<template>
  <form @submit.prevent="handleSubmit" class="space-y-8">
    <!-- Product Image Upload -->
    <div class="space-y-4">
      <div class="flex items-center space-x-3 mb-4">
        <div class="w-8 h-8 bg-orange-100 dark:bg-orange-900/30 rounded-full flex items-center justify-center">
          <Camera class="w-4 h-4 text-orange-600 dark:text-orange-400" />
        </div>
        <label class="text-lg font-semibold text-gray-900 dark:text-white">
          🖼️ Imagen del Producto
        </label>
      </div>
      <ImageUpload
        v-model="form.imagen_url"
        :alt="form.nombre || 'Imagen del producto'"
        @file-change="handleImageChange"
      />
    </div>

    <!-- Product Name -->
    <div class="space-y-4">
      <div class="flex items-center space-x-3 mb-4">
        <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
          <Tag class="w-4 h-4 text-blue-600 dark:text-blue-400" />
        </div>
        <label for="nombre" class="text-lg font-semibold text-gray-900 dark:text-white">
          🍔 Nombre del Producto *
        </label>
      </div>
      <input
        id="nombre"
        v-model="form.nombre"
        type="text"
        required
        maxlength="80"
        class="input-restaurant w-full"
        :class="{ 'border-red-500 focus:ring-red-500': errors?.nombre }"
        placeholder="Ej: Hamburguesa Clásica"
      />
      <p v-if="errors?.nombre" class="text-sm text-red-600 flex items-center">
        <AlertCircle class="w-4 h-4 mr-1" />
        {{ errors.nombre }}
      </p>
    </div>

    <!-- Product Description -->
    <div class="space-y-4">
      <div class="flex items-center space-x-3 mb-4">
        <div class="w-8 h-8 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center">
          <FileText class="w-4 h-4 text-green-600 dark:text-green-400" />
        </div>
        <label for="descripcion" class="text-lg font-semibold text-gray-900 dark:text-white">
          📝 Descripción
        </label>
      </div>
      <textarea
        id="descripcion"
        v-model="form.descripcion"
        rows="4"
        maxlength="255"
        class="input-restaurant w-full resize-none"
        :class="{ 'border-red-500 focus:ring-red-500': errors?.descripcion }"
        placeholder="Describe los ingredientes y características del producto..."
      ></textarea>
      <div class="flex justify-between items-center">
        <p v-if="errors?.descripcion" class="text-sm text-red-600 flex items-center">
          <AlertCircle class="w-4 h-4 mr-1" />
          {{ errors.descripcion }}
        </p>
        <span class="text-sm text-gray-500 font-medium">
          {{ form.descripcion?.length || 0 }}/255
        </span>
      </div>
    </div>

    <!-- Category Selection -->
    <div class="space-y-4">
      <div class="flex items-center space-x-3 mb-4">
        <div class="w-8 h-8 bg-purple-100 dark:bg-purple-900/30 rounded-full flex items-center justify-center">
          <FolderOpen class="w-4 h-4 text-purple-600 dark:text-purple-400" />
        </div>
        <label for="categoria_id" class="text-lg font-semibold text-gray-900 dark:text-white">
          📁 Categoría *
        </label>
      </div>
      <select
        id="categoria_id"
        v-model="form.categoria_id"
        required
        class="input-restaurant w-full"
        :class="{ 'border-red-500 focus:ring-red-500': errors?.categoria_id }"
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
      <p v-if="errors?.categoria_id" class="text-sm text-red-600 flex items-center">
        <AlertCircle class="w-4 h-4 mr-1" />
        {{ errors.categoria_id }}
      </p>
    </div>

    <!-- Price -->
    <div class="space-y-4">
      <div class="flex items-center space-x-3 mb-4">
        <div class="w-8 h-8 bg-yellow-100 dark:bg-yellow-900/30 rounded-full flex items-center justify-center">
          <DollarSign class="w-4 h-4 text-yellow-600 dark:text-yellow-400" />
        </div>
        <label for="precio" class="text-lg font-semibold text-gray-900 dark:text-white">
          💰 Precio *
        </label>
      </div>
      <div class="relative">
        <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500 font-semibold">$</span>
        <input
          id="precio"
          v-model="form.precio"
          type="number"
          step="0.01"
          min="0"
          required
          class="input-restaurant w-full pl-8"
          :class="{ 'border-red-500 focus:ring-red-500': errors?.precio }"
          placeholder="0.00"
        />
      </div>
      <p v-if="errors?.precio" class="text-sm text-red-600 flex items-center">
        <AlertCircle class="w-4 h-4 mr-1" />
        {{ errors.precio }}
      </p>
    </div>

    <!-- Action Buttons -->
    <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4 pt-6 border-t border-orange-200 dark:border-orange-800">
      <button
        type="submit"
        :disabled="isSubmitting"
        class="btn-restaurant flex-1 inline-flex items-center justify-center"
      >
        <Loader2 v-if="isSubmitting" class="w-5 h-5 mr-2 animate-spin" />
        <Plus v-else class="w-5 h-5 mr-2" />
        {{ isSubmitting ? 'Creando Producto...' : 'Crear Producto' }}
      </button>
      
      <button
        type="button"
        @click="$emit('cancel')"
        class="btn-restaurant-secondary flex-1 inline-flex items-center justify-center"
      >
        <X class="w-5 h-5 mr-2" />
        Cancelar
      </button>
    </div>
  </form>
</template>

<script setup lang="ts">
import { ref, reactive, computed, onMounted } from 'vue'
import { Loader2, Plus, X, Camera, Tag, AlertCircle, FileText, FolderOpen, DollarSign } from 'lucide-vue-next'
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