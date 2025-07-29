<script setup lang="ts">
import { ref } from 'vue'
import { Menu, Search, Bell, User, ChevronDown, Settings, LogOut } from 'lucide-vue-next'
import Breadcrumbs from './Breadcrumbs.vue'

const showUserMenu = ref(false)

const toggleSidebar = () => {
  // Emit event to toggle sidebar
  console.log('Toggle sidebar')
}

const toggleUserMenu = () => {
  showUserMenu.value = !showUserMenu.value
}

const logout = () => {
  // Handle logout
  console.log('Logout')
}
</script>

<template>
  <header class="header-restaurant border-b border-orange-200 dark:border-orange-800">
    <div class="flex h-16 items-center justify-between px-6">
      <!-- Left Section -->
      <div class="flex items-center space-x-4">
        <button
          @click="toggleSidebar"
          class="p-2 rounded-lg bg-white/10 hover:bg-white/20 transition-colors duration-200"
        >
          <Menu class="w-5 h-5 text-white" />
        </button>
        
        <!-- Breadcrumbs -->
        <nav class="flex items-center space-x-2 text-white/80">
          <Breadcrumbs :breadcrumbs="[]" />
        </nav>
      </div>

      <!-- Right Section -->
      <div class="flex items-center space-x-4">
        <!-- Search -->
        <div class="relative">
          <input
            type="text"
            placeholder="Buscar productos..."
            class="w-64 px-4 py-2 pl-10 bg-white/10 border border-white/20 rounded-lg text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-white/30 focus:border-white/30 transition-all duration-200"
          />
          <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-white/60" />
        </div>

        <!-- Notifications -->
        <button class="relative p-2 rounded-lg bg-white/10 hover:bg-white/20 transition-colors duration-200">
          <Bell class="w-5 h-5 text-white" />
          <span class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full"></span>
        </button>

        <!-- User Menu -->
        <div class="relative">
          <button
            @click="toggleUserMenu"
            class="flex items-center space-x-2 p-2 rounded-lg bg-white/10 hover:bg-white/20 transition-colors duration-200"
          >
            <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center">
              <User class="w-4 h-4 text-white" />
            </div>
            <span class="text-white font-medium">Admin</span>
            <ChevronDown class="w-4 h-4 text-white/80" />
          </button>

          <!-- User Dropdown -->
          <div
            v-if="showUserMenu"
            class="absolute right-0 top-full mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-xl border border-orange-200 dark:border-orange-800 z-50"
          >
            <div class="py-2">
              <a
                href="/profile"
                class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-orange-50 dark:hover:bg-orange-900/20 transition-colors"
              >
                <User class="w-4 h-4 mr-2" />
                Mi Perfil
              </a>
              <a
                href="/settings"
                class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-orange-50 dark:hover:bg-orange-900/20 transition-colors"
              >
                <Settings class="w-4 h-4 mr-2" />
                Configuración
              </a>
              <hr class="my-2 border-orange-200 dark:border-orange-800" />
              <button
                @click="logout"
                class="flex items-center w-full px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors"
              >
                <LogOut class="w-4 h-4 mr-2" />
                Cerrar Sesión
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>
</template>
