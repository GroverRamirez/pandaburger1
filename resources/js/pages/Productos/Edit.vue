<template>
  <AppLayout :breadcrumbs="breadcrumbs">
    <Head title="Editar Producto - Pandaburger" />

    <div class="min-h-screen bg-gradient-to-br from-orange-50 via-orange-100 to-orange-200 dark:from-gray-900 dark:via-orange-900/20 dark:to-orange-800/30">
      <!-- Header Section -->
      <div class="bg-gradient-to-r from-orange-500 to-orange-600 shadow-2xl border-b border-orange-400">
        <div class="max-w-7xl mx-auto px-6 py-12">
          <div class="flex items-center justify-between">
            <div>
              <h1 class="text-4xl font-bold text-white mb-2 animate-fade-in-up">
                ✏️ Editar Producto
              </h1>
              <p class="text-xl text-orange-100 font-medium">
                Modifica la información del producto
              </p>
              <div class="mt-4 flex items-center space-x-4">
                <div class="flex items-center space-x-2 bg-white/20 rounded-full px-4 py-2">
                  <span class="text-orange-100">🍔</span>
                  <span class="text-white font-medium">{{ producto.nombre }}</span>
                </div>
                <div class="flex items-center space-x-2 bg-white/20 rounded-full px-4 py-2">
                  <span class="text-orange-100">💰</span>
                  <span class="text-white font-medium">${{ formatPrice(producto.precio) }}</span>
                </div>
              </div>
            </div>
            <div class="flex space-x-3">
              <Link
                :href="route('productos.show', producto.id)"
                class="btn-restaurant-secondary inline-flex items-center"
              >
                <Eye class="w-5 h-5 mr-2" />
                Ver Detalles
              </Link>
              <Link
                :href="route('productos.index')"
                class="btn-restaurant-secondary inline-flex items-center"
              >
                <ArrowLeft class="w-5 h-5 mr-2" />
                Volver a Productos
              </Link>
            </div>
          </div>
        </div>
      </div>

      <!-- Main Content -->
      <div class="max-w-4xl mx-auto px-6 py-8">
        <div class="restaurant-card">
          <div class="p-8">
            <!-- Form Header -->
            <div class="text-center mb-8">
              <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                <Edit class="w-10 h-10 text-white" />
              </div>
              <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                Información del Producto
              </h2>
              <p class="text-gray-600 dark:text-gray-400">
                Modifica los detalles de tu producto
              </p>
            </div>

            <!-- Product Form -->
            <ProductForm
              :producto="producto"
              :categorias="categorias"
              :errors="errors"
              @submit="handleSubmit"
              @cancel="handleCancel"
            />
          </div>
        </div>

        <!-- Product Preview -->
        <div class="mt-8">
          <div class="restaurant-card">
            <div class="p-6">
              <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                  👀 Vista Previa del Producto
                </h3>
                <div class="w-8 h-8 bg-orange-100 dark:bg-orange-900/30 rounded-full flex items-center justify-center">
                  <Eye class="w-4 h-4 text-orange-600 dark:text-orange-400" />
                </div>
              </div>
              
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Product Image Preview -->
                <div class="space-y-4">
                  <h4 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Imagen Actual
                  </h4>
                  <div class="food-image-container aspect-square">
                    <img
                      v-if="producto.imagen_url"
                      :src="producto.imagen_url"
                      :alt="producto.nombre"
                      class="w-full h-full object-cover rounded-xl"
                    />
                    <div
                      v-else
                      class="w-full h-full bg-orange-100 dark:bg-orange-900/30 rounded-xl flex items-center justify-center"
                    >
                      <div class="text-center">
                        <Package class="w-16 h-16 text-orange-400 mx-auto mb-2" />
                        <p class="text-lg text-orange-600 dark:text-orange-400 font-medium">Sin imagen</p>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Product Info Preview -->
                <div class="space-y-4">
                  <h4 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Información Actual
                  </h4>
                  <div class="space-y-3">
                    <div class="flex items-center justify-between p-3 bg-gradient-to-r from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20 rounded-xl border border-orange-200 dark:border-orange-800">
                      <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Nombre:</span>
                      <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ producto.nombre }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between p-3 bg-gradient-to-r from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-xl border border-green-200 dark:border-green-800">
                      <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Categoría:</span>
                      <span class="food-badge">{{ producto.categoria?.nombre }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between p-3 bg-gradient-to-r from-yellow-50 to-yellow-100 dark:from-yellow-900/20 dark:to-yellow-800/20 rounded-xl border border-yellow-200 dark:border-yellow-800">
                      <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Precio:</span>
                      <span class="price-display">${{ formatPrice(producto.precio) }}</span>
                    </div>
                    
                    <div v-if="producto.descripcion" class="p-3 bg-gradient-to-r from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-xl border border-blue-200 dark:border-blue-800">
                      <span class="text-sm font-medium text-gray-600 dark:text-gray-400 block mb-1">Descripción:</span>
                      <p class="text-sm text-gray-700 dark:text-gray-300">{{ producto.descripcion }}</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Tips Section -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
          <div class="restaurant-card p-6 text-center">
            <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900/30 rounded-full flex items-center justify-center mx-auto mb-3">
              <Camera class="w-6 h-6 text-orange-600 dark:text-orange-400" />
            </div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
              Imagen Actualizada
            </h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">
              Cambia la imagen para mejorar la presentación
            </p>
          </div>

          <div class="restaurant-card p-6 text-center">
            <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mx-auto mb-3">
              <FileText class="w-6 h-6 text-green-600 dark:text-green-400" />
            </div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
              Descripción Mejorada
            </h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">
              Actualiza la descripción para ser más atractiva
            </p>
          </div>

          <div class="restaurant-card p-6 text-center">
            <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-full flex items-center justify-center mx-auto mb-3">
              <Tag class="w-6 h-6 text-purple-600 dark:text-purple-400" />
            </div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
              Precio Competitivo
            </h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">
              Ajusta el precio según el mercado actual
            </p>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3'
import { ArrowLeft, Eye, Edit, Camera, FileText, Tag, Package } from 'lucide-vue-next'
import AppLayout from '@/layouts/AppLayout.vue'
import ProductForm from '@/components/Productos/ProductForm.vue'
import type { BreadcrumbItem } from '@/types'
import type { Producto, Categoria } from '@/types/productos'

interface Props {
  producto: Producto
  categorias: Categoria[]
  errors?: Record<string, string>
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
  {
    title: 'Editar',
    href: `/productos/${props.producto.id}/edit`,
  },
]

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

const handleSubmit = (formData: FormData) => {
  router.put(route('productos.update', props.producto.id), formData, {
    onSuccess: () => {
      // Success message will be handled by the controller
    },
    onError: (errors) => {
      console.error('Validation errors:', errors)
    },
  })
}

const handleCancel = () => {
  router.visit(route('productos.index'))
}
</script> 