<template>
  <AppLayout :breadcrumbs="breadcrumbs">
    <Head title="Crear Producto" />

    <div class="flex h-full flex-1 flex-col gap-6 p-6">
      <!-- Header -->
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
            Crear Nuevo Producto
          </h1>
          <p class="text-gray-600 dark:text-gray-400">
            Añade un nuevo producto al catálogo de Pandaburger
          </p>
        </div>
        <Link
          :href="route('productos.index')"
          class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
        >
          <ArrowLeft class="w-4 h-4 mr-2" />
          Volver
        </Link>
      </div>

      <!-- Form -->
      <div class="max-w-2xl">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
          <ProductForm
            :categorias="categorias"
            :errors="errors"
            @submit="handleSubmit"
            @cancel="handleCancel"
          />
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3'
import { ArrowLeft } from 'lucide-vue-next'
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