<template>
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total de Usuarios -->
    <div class="bg-gradient-to-br from-orange-500 to-red-500 rounded-xl p-6 text-white shadow-lg">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-orange-100 text-sm font-medium">Total de Usuarios</p>
          <p class="text-3xl font-bold">{{ stats.total_users || 0 }}</p>
        </div>
        <div class="flex items-center justify-center w-12 h-12 bg-white/20 rounded-full">
          <Icon name="users" class="w-6 h-6 text-white" />
        </div>
      </div>
    </div>

    <!-- Usuarios Activos -->
    <div class="bg-gradient-to-br from-green-500 to-emerald-500 rounded-xl p-6 text-white shadow-lg">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-green-100 text-sm font-medium">Usuarios Activos</p>
          <p class="text-3xl font-bold">{{ stats.active_users || 0 }}</p>
        </div>
        <div class="flex items-center justify-center w-12 h-12 bg-white/20 rounded-full">
          <Icon name="user-check" class="w-6 h-6 text-white" />
        </div>
      </div>
    </div>

    <!-- Usuarios Inactivos -->
    <div class="bg-gradient-to-br from-red-500 to-pink-500 rounded-xl p-6 text-white shadow-lg">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-red-100 text-sm font-medium">Usuarios Inactivos</p>
          <p class="text-3xl font-bold">{{ stats.inactive_users || 0 }}</p>
        </div>
        <div class="flex items-center justify-center w-12 h-12 bg-white/20 rounded-full">
          <Icon name="user-x" class="w-6 h-6 text-white" />
        </div>
      </div>
    </div>

    <!-- Nuevos este Mes -->
    <div class="bg-gradient-to-br from-blue-500 to-indigo-500 rounded-xl p-6 text-white shadow-lg">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-blue-100 text-sm font-medium">Nuevos este Mes</p>
          <p class="text-3xl font-bold">{{ stats.new_this_month || 0 }}</p>
        </div>
        <div class="flex items-center justify-center w-12 h-12 bg-white/20 rounded-full">
          <Icon name="user-plus" class="w-6 h-6 text-white" />
        </div>
      </div>
    </div>
  </div>

  <!-- Gráfico de Roles -->
  <div class="bg-white rounded-xl border-2 border-orange-200 p-6 shadow-lg mb-8">
    <div class="flex items-center gap-2 mb-4">
      <Icon name="pie-chart" class="w-5 h-5 text-orange-600" />
      <h3 class="text-lg font-semibold text-orange-800">Distribución por Roles</h3>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <div v-for="(count, role) in stats.users_by_role" :key="role" 
           class="bg-gradient-to-r from-orange-50 to-amber-50 rounded-lg p-4 border border-orange-200">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-orange-800 font-semibold">{{ role }}</p>
            <p class="text-2xl font-bold text-orange-600">{{ count }}</p>
          </div>
          <div class="flex items-center justify-center w-10 h-10 bg-orange-100 rounded-full">
            <Icon name="shield" class="w-5 h-5 text-orange-600" />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import Icon from '@/components/Icon.vue'

interface UserStats {
  total_users: number
  active_users: number
  inactive_users: number
  new_this_month: number
  users_by_role: Record<string, number>
}

interface Props {
  stats: UserStats
}

defineProps<Props>()
</script> 