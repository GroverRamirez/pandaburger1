<template>
  <div class="space-y-4">
    <!-- Image Preview -->
    <div class="flex justify-center">
      <div class="relative">
        <div
          class="w-32 h-32 rounded-lg overflow-hidden border-2 border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-700"
        >
          <img
            v-if="previewUrl || modelValue"
            :src="previewUrl || modelValue"
            :alt="alt"
            class="w-full h-full object-cover"
            @error="handleImageError"
          />
          <div
            v-else
            class="w-full h-full flex items-center justify-center"
          >
            <Upload class="w-8 h-8 text-gray-400" />
          </div>
        </div>
        
        <!-- Remove Button -->
        <button
          v-if="previewUrl || modelValue"
          @click="removeImage"
          type="button"
          class="absolute -top-2 -right-2 bg-red-500 hover:bg-red-600 text-white rounded-full p-1 transition-colors"
          title="Eliminar imagen"
        >
          <X class="w-4 h-4" />
        </button>
      </div>
    </div>

    <!-- Upload Area -->
    <div class="flex justify-center">
      <div class="w-full max-w-xs">
        <label
          :for="inputId"
          class="cursor-pointer inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
        >
          <Upload class="w-4 h-4 mr-2" />
          {{ previewUrl || modelValue ? 'Cambiar imagen' : 'Subir imagen' }}
        </label>
        <input
          :id="inputId"
          ref="fileInput"
          type="file"
          accept="image/*"
          class="hidden"
          @change="handleFileChange"
        />
      </div>
    </div>

    <!-- Help Text -->
    <p class="text-xs text-gray-500 dark:text-gray-400 text-center">
      PNG, JPG, GIF hasta 2MB
    </p>

    <!-- Error Message -->
    <p
      v-if="error"
      class="text-xs text-red-500 text-center"
    >
      {{ error }}
    </p>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { Upload, X } from 'lucide-vue-next'

interface Props {
  modelValue?: string
  alt?: string
  maxSize?: number // in MB
}

const props = withDefaults(defineProps<Props>(), {
  alt: 'Imagen del producto',
  maxSize: 2
})

const emit = defineEmits<{
  'update:modelValue': [value: string]
  'file-change': [file: File]
}>()

const fileInput = ref<HTMLInputElement>()
const previewUrl = ref<string>('')
const error = ref<string>('')

const inputId = computed(() => `image-upload-${Math.random().toString(36).substr(2, 9)}`)

const handleFileChange = (event: Event) => {
  const target = event.target as HTMLInputElement
  const file = target.files?.[0]
  
  if (!file) return
  
  // Validate file size
  const maxSizeBytes = props.maxSize * 1024 * 1024
  if (file.size > maxSizeBytes) {
    error.value = `El archivo es demasiado grande. Máximo ${props.maxSize}MB.`
    return
  }
  
  // Validate file type
  if (!file.type.startsWith('image/')) {
    error.value = 'Solo se permiten archivos de imagen.'
    return
  }
  
  error.value = ''
  
  // Create preview
  const reader = new FileReader()
  reader.onload = (e) => {
    previewUrl.value = e.target?.result as string
  }
  reader.readAsDataURL(file)
  
  // Emit file
  emit('file-change', file)
}

const handleImageError = () => {
  error.value = 'Error al cargar la imagen.'
}

const removeImage = () => {
  previewUrl.value = ''
  if (fileInput.value) {
    fileInput.value.value = ''
  }
  emit('update:modelValue', '')
  emit('file-change', null as any)
}
</script> 