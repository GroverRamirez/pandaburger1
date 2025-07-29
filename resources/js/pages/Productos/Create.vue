<template>
  <AppLayout :breadcrumbs="breadcrumbs">
    <Head title="Crear Producto - Pandaburger" />

    <div class="min-h-screen bg-gradient-to-br from-orange-50 via-orange-100 to-orange-200 dark:from-gray-900 dark:via-orange-900/20 dark:to-orange-800/30">
      <!-- Header Section -->
      <div class="bg-gradient-to-r from-orange-500 to-orange-600 shadow-2xl border-b border-orange-400">
        <div class="max-w-7xl mx-auto px-6 py-12">
          <div class="flex items-center justify-between">
            <div>
              <h1 class="text-4xl font-bold text-white mb-2 animate-fade-in-up">
                🍔 Crear Nuevo Producto
              </h1>
              <p class="text-xl text-orange-100 font-medium">
                Añade un nuevo producto al catálogo de Pandaburger
              </p>
            </div>
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

      <!-- Main Content -->
      <div class="max-w-4xl mx-auto px-6 py-8">
        <div class="restaurant-card">
          <div class="p-8">
            <!-- Form Header -->
            <div class="text-center mb-8">
              <div class="w-20 h-20 bg-gradient-to-br from-orange-500 to-orange-600 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                <Plus class="w-10 h-10 text-white" />
              </div>
              <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                Información del Producto
              </h2>
              <p class="text-gray-600 dark:text-gray-400">
                Completa los detalles para crear tu nuevo producto
              </p>
            </div>

            <!-- Product Form -->
            <ProductForm
              :categorias="categorias"
              :errors="errors"
              @submit="handleSubmit"
              @cancel="handleCancel"
            />
          </div>
        </div>

        <!-- Tips Section -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
          <div class="restaurant-card p-6 text-center">
            <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center mx-auto mb-3">
              <Camera class="w-6 h-6 text-blue-600 dark:text-blue-400" />
            </div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
              Imagen Atractiva
            </h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">
              Usa imágenes de alta calidad para mostrar tu producto
            </p>
          </div>

          <div class="restaurant-card p-6 text-center">
            <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mx-auto mb-3">
              <FileText class="w-6 h-6 text-green-600 dark:text-green-400" />
            </div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
              Descripción Clara
            </h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">
              Describe los ingredientes y características del producto
            </p>
          </div>

          <div class="restaurant-card p-6 text-center">
            <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-full flex items-center justify-center mx-auto mb-3">
              <Tag class="w-6 h-6 text-purple-600 dark:text-purple-400" />
            </div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
              Categoría Correcta
            </h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">
              Organiza tu producto en la categoría adecuada
            </p>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3'
import { ArrowLeft, Plus, Camera, FileText, Tag } from 'lucide-vue-next'
import AppLayout from '@/layouts/AppLayout.vue'
import ProductForm from '@/components/Productos/ProductForm.vue'
import type { BreadcrumbItem } from '@/types'
import type { Categoria } from '@/types/productos'

interface Props {
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
    title: 'Crear Producto',
    href: '/productos/create',
  },
]

const handleSubmit = (formData: FormData) => {
  router.post(route('productos.store'), formData, {
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