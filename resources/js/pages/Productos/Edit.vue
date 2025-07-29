<template>
  <AppLayout :breadcrumbs="breadcrumbs">
    <Head title="Editar Producto" />

    <div class="flex h-full flex-1 flex-col gap-6 p-6">
      <!-- Header -->
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
            Editar Producto
          </h1>
          <p class="text-gray-600 dark:text-gray-400">
            Modifica la información del producto
          </p>
        </div>
        <div class="flex items-center space-x-3">
          <Link
            :href="route('productos.show', producto.id)"
            class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
          >
            <Eye class="w-4 h-4 mr-2" />
            Ver Detalles
          </Link>
          <Link
            :href="route('productos.index')"
            class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
          >
            <ArrowLeft class="w-4 h-4 mr-2" />
            Volver
          </Link>
        </div>
      </div>

      <!-- Form -->
      <div class="max-w-2xl">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
          <ProductForm
            :producto="producto"
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
import { ArrowLeft, Eye } from 'lucide-vue-next'
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

const handleSubmit = (formData: FormData) => {
  router.post(route('productos.update', props.producto.id), formData, {
    method: 'put',
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