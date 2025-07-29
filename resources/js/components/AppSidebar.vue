<script setup lang="ts">
import { computed } from 'vue'
import { usePage, Link } from '@inertiajs/vue3'
import {
  Sidebar,
  SidebarContent,
  SidebarFooter,
  SidebarGroup,
  SidebarGroupLabel,
  SidebarHeader,
  SidebarMenu,
  SidebarMenuItem,
  SidebarMenuButton,
} from '@/components/ui/sidebar'
import AppLogo from './AppLogo.vue'
import { mainNavItems, productNavItems, managementNavItems, reportsNavItems, configNavItems } from '@/config/navigation'
import { Github, FileText } from 'lucide-vue-next'

const page = usePage()

const isActive = (href: string) => {
  const currentPath = page.url
  return currentPath === href || currentPath.startsWith(href + '/')
}
</script>

<template>
  <Sidebar class="sidebar-restaurant">
    <SidebarHeader class="border-b border-orange-200 dark:border-orange-800">
      <AppLogo />
    </SidebarHeader>

    <SidebarContent class="py-4">
      <!-- Platform Section -->
      <SidebarGroup>
        <SidebarGroupLabel class="text-xs font-semibold text-orange-700 dark:text-orange-300 uppercase tracking-wider px-4 mb-2">
          Plataforma
        </SidebarGroupLabel>
        <SidebarMenu>
          <SidebarMenuItem v-for="item in mainNavItems" :key="item.href">
            <Link :href="item.href" class="w-full">
              <SidebarMenuButton :active="isActive(item.href)">
                <component :is="item.icon" class="w-4 h-4" />
                {{ item.title }}
              </SidebarMenuButton>
            </Link>
          </SidebarMenuItem>
        </SidebarMenu>
      </SidebarGroup>

      <!-- Product Management Section -->
      <SidebarGroup>
        <SidebarGroupLabel class="text-xs font-semibold text-orange-700 dark:text-orange-300 uppercase tracking-wider px-4 mb-2">
          Gestión de Productos
        </SidebarGroupLabel>
        <SidebarMenu>
          <SidebarMenuItem v-for="item in productNavItems" :key="item.href">
            <Link :href="item.href" class="w-full">
              <SidebarMenuButton :active="isActive(item.href)">
                <component :is="item.icon" class="w-4 h-4" />
                {{ item.title }}
              </SidebarMenuButton>
            </Link>
          </SidebarMenuItem>
        </SidebarMenu>
      </SidebarGroup>

      <!-- Management Section -->
      <SidebarGroup>
        <SidebarGroupLabel class="text-xs font-semibold text-orange-700 dark:text-orange-300 uppercase tracking-wider px-4 mb-2">
          Gestión
        </SidebarGroupLabel>
        <SidebarMenu>
          <SidebarMenuItem v-for="item in managementNavItems" :key="item.href">
            <Link :href="item.href" class="w-full">
              <SidebarMenuButton :active="isActive(item.href)">
                <component :is="item.icon" class="w-4 h-4" />
                {{ item.title }}
              </SidebarMenuButton>
            </Link>
          </SidebarMenuItem>
        </SidebarMenu>
      </SidebarGroup>

      <!-- Reports Section -->
      <SidebarGroup>
        <SidebarGroupLabel class="text-xs font-semibold text-orange-700 dark:text-orange-300 uppercase tracking-wider px-4 mb-2">
          Reportes
        </SidebarGroupLabel>
        <SidebarMenu>
          <SidebarMenuItem v-for="item in reportsNavItems" :key="item.href">
            <Link :href="item.href" class="w-full">
              <SidebarMenuButton :active="isActive(item.href)">
                <component :is="item.icon" class="w-4 h-4" />
                {{ item.title }}
              </SidebarMenuButton>
            </Link>
          </SidebarMenuItem>
        </SidebarMenu>
      </SidebarGroup>

      <!-- Configuration Section -->
      <SidebarGroup>
        <SidebarGroupLabel class="text-xs font-semibold text-orange-700 dark:text-orange-300 uppercase tracking-wider px-4 mb-2">
          Configuración
        </SidebarGroupLabel>
        <SidebarMenu>
          <SidebarMenuItem v-for="item in configNavItems" :key="item.href">
            <Link :href="item.href" class="w-full">
              <SidebarMenuButton :active="isActive(item.href)">
                <component :is="item.icon" class="w-4 h-4" />
                {{ item.title }}
              </SidebarMenuButton>
            </Link>
          </SidebarMenuItem>
        </SidebarMenu>
      </SidebarGroup>
    </SidebarContent>

    <SidebarFooter class="border-t border-orange-200 dark:border-orange-800">
      <div class="p-4 space-y-2">
        <a
          href="https://github.com"
          target="_blank"
          class="flex items-center space-x-2 text-sm text-orange-600 dark:text-orange-400 hover:text-orange-700 dark:hover:text-orange-300 transition-colors"
        >
          <Github class="w-4 h-4" />
          <span>Github Repo</span>
        </a>
        <a
          href="/docs"
          class="flex items-center space-x-2 text-sm text-orange-600 dark:text-orange-400 hover:text-orange-700 dark:hover:text-orange-300 transition-colors"
        >
          <FileText class="w-4 h-4" />
          <span>Documentación</span>
        </a>
      </div>
    </SidebarFooter>
  </Sidebar>
</template>
