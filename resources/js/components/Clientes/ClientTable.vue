<script setup lang="ts">
import { computed } from 'vue';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuTrigger,
  DropdownMenuSeparator,
} from '@/components/ui/dropdown-menu';
import { Link } from '@inertiajs/vue3';
import { 
  MoreHorizontal, 
  Eye, 
  Edit, 
  Trash2, 
  Mail, 
  Phone, 
  MapPin, 
  Calendar,
  RefreshCw,
  Users,
  Package
} from 'lucide-vue-next';
import type { Cliente } from '@/types/client';

interface Props {
  clientes: Cliente[];
  loading?: boolean;
  currentPage: number;
  totalPages: number;
  perPage: number;
}

interface Emits {
  (e: 'delete', id: number): void;
  (e: 'pageChange', page: number): void;
  (e: 'perPageChange', perPage: number): void;
}

const props = defineProps<Props>();
const emit = defineEmits<Emits>();

const formatDate = (dateString: string) => {
  return new Date(dateString).toLocaleDateString('es-ES', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  });
};

const formatRelativeDate = (dateString: string) => {
  const now = new Date();
  const date = new Date(dateString);
  const diffTime = Math.abs(now.getTime() - date.getTime());
  const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
  
  if (diffDays === 1) return 'Hoy';
  if (diffDays === 2) return 'Ayer';
  if (diffDays <= 7) return `Hace ${diffDays - 1} días`;
  if (diffDays <= 30) return `Hace ${Math.floor(diffDays / 7)} semanas`;
  if (diffDays <= 365) return `Hace ${Math.floor(diffDays / 30)} meses`;
  return `Hace ${Math.floor(diffDays / 365)} años`;
};

const getCustomerStatus = (cliente: Cliente) => {
  const lastLogin = cliente.last_login_at ? new Date(cliente.last_login_at) : null;
  const created = new Date(cliente.created_at);
  const now = new Date();
  
  // Cliente nuevo (menos de 30 días)
  if ((now.getTime() - created.getTime()) / (1000 * 60 * 60 * 24) <= 30) {
    return { status: 'new', label: 'Nuevo', color: 'bg-blue-100 text-blue-800 border-blue-200' };
  }
  
  // Cliente activo (último login en los últimos 7 días)
  if (lastLogin && (now.getTime() - lastLogin.getTime()) / (1000 * 60 * 60 * 24) <= 7) {
    return { status: 'active', label: 'Activo', color: 'bg-green-100 text-green-800 border-green-200' };
  }
  
  // Cliente inactivo
  return { status: 'inactive', label: 'Inactivo', color: 'bg-gray-100 text-gray-800 border-gray-200' };
};

const handleDelete = (id: number) => {
  if (confirm('¿Estás seguro de que quieres eliminar este cliente?')) {
    emit('delete', id);
  }
};

const handlePageChange = (page: number) => {
  emit('pageChange', page);
};

const handlePerPageChange = (perPage: number) => {
  emit('perPageChange', perPage);
};

const pageNumbers = computed(() => {
  const pages = [];
  const maxVisible = 5;
  const start = Math.max(1, props.currentPage - Math.floor(maxVisible / 2));
  const end = Math.min(props.totalPages, start + maxVisible - 1);
  
  for (let i = start; i <= end; i++) {
    pages.push(i);
  }
  
  return pages;
});
</script>

<template>
  <div class="space-y-4">
    <!-- Table -->
    <div class="overflow-x-auto border border-gray-200 dark:border-gray-700 rounded-lg">
      <Table>
        <TableHeader>
          <TableRow class="bg-gray-50 dark:bg-gray-800">
            <TableHead class="w-12">Cliente</TableHead>
            <TableHead>Contacto</TableHead>
            <TableHead>Ubicación</TableHead>
            <TableHead>Estado</TableHead>
            <TableHead>Registro</TableHead>
            <TableHead class="w-16 text-right">Acciones</TableHead>
          </TableRow>
        </TableHeader>
        <TableBody>
          <!-- Loading State -->
          <TableRow v-if="loading" class="animate-pulse">
            <TableCell colspan="6" class="text-center py-12">
              <div class="flex items-center justify-center space-x-2">
                <RefreshCw class="w-5 h-5 animate-spin" />
                <span>Cargando clientes...</span>
              </div>
            </TableCell>
          </TableRow>
          
          <!-- Empty State -->
          <TableRow v-else-if="clientes.length === 0" class="hover:bg-transparent">
            <TableCell colspan="6" class="text-center py-12">
              <div class="text-gray-500 dark:text-gray-400">
                <Users class="w-12 h-12 mx-auto mb-4 text-gray-300" />
                <p class="text-lg font-medium">No se encontraron clientes</p>
                <p class="text-sm">Intenta ajustar los filtros de búsqueda</p>
              </div>
            </TableCell>
          </TableRow>
          
          <!-- Client Rows -->
          <TableRow 
            v-for="cliente in clientes" 
            :key="cliente.id"
            class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors"
          >
            <!-- Cliente Info -->
            <TableCell>
              <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-br from-orange-400 to-red-500 rounded-full flex items-center justify-center text-white font-semibold">
                  {{ cliente.nombre.charAt(0).toUpperCase() }}
                </div>
                <div>
                  <p class="font-medium text-gray-900 dark:text-white">{{ cliente.nombre }}</p>
                  <p class="text-sm text-gray-500 dark:text-gray-400">ID: #{{ cliente.id }}</p>
                </div>
              </div>
            </TableCell>
            
            <!-- Contact Info -->
            <TableCell>
              <div class="space-y-1">
                <div v-if="cliente.correo_electronico" class="flex items-center space-x-2">
                  <Mail class="w-3 h-3 text-gray-400" />
                  <span class="text-sm">{{ cliente.correo_electronico }}</span>
                </div>
                <div v-if="cliente.telefono" class="flex items-center space-x-2">
                  <Phone class="w-3 h-3 text-gray-400" />
                  <span class="text-sm">{{ cliente.telefono }}</span>
                </div>
                <div v-if="!cliente.correo_electronico && !cliente.telefono" class="text-sm text-gray-400">
                  Sin información de contacto
                </div>
              </div>
            </TableCell>
            
            <!-- Location -->
            <TableCell>
              <div v-if="cliente.direccion" class="flex items-center space-x-2">
                <MapPin class="w-3 h-3 text-gray-400" />
                <span class="text-sm text-gray-600 dark:text-gray-400">{{ cliente.direccion }}</span>
              </div>
              <span v-else class="text-sm text-gray-400">No especificada</span>
            </TableCell>
            
            <!-- Status -->
            <TableCell>
              <Badge :class="getCustomerStatus(cliente).color" class="text-xs">
                {{ getCustomerStatus(cliente).label }}
              </Badge>
            </TableCell>
            
            <!-- Registration Date -->
            <TableCell>
              <div class="space-y-1">
                <div class="flex items-center space-x-2">
                  <Calendar class="w-3 h-3 text-gray-400" />
                  <span class="text-sm font-medium">{{ formatDate(cliente.created_at) }}</span>
                </div>
                <p class="text-xs text-gray-500">{{ formatRelativeDate(cliente.created_at) }}</p>
              </div>
            </TableCell>
            
            <!-- Actions -->
            <TableCell class="text-right">
              <DropdownMenu>
                <DropdownMenuTrigger as-child>
                  <Button variant="ghost" size="icon" class="h-8 w-8">
                    <MoreHorizontal class="w-4 h-4" />
                  </Button>
                </DropdownMenuTrigger>
                <DropdownMenuContent align="end" class="w-48">
                  <DropdownMenuItem as-child>
                    <Link :href="route('clientes.show', cliente.id)" class="flex items-center">
                      <Eye class="w-4 h-4 mr-2" />
                      Ver Perfil
                    </Link>
                  </DropdownMenuItem>
                  <DropdownMenuItem as-child>
                    <Link :href="route('clientes.edit', cliente.id)" class="flex items-center">
                      <Edit class="w-4 h-4 mr-2" />
                      Editar
                    </Link>
                  </DropdownMenuItem>
                  <DropdownMenuSeparator />
                  <DropdownMenuItem 
                    @click="handleDelete(cliente.id)"
                    class="text-red-600 focus:text-red-600 focus:bg-red-50 dark:focus:bg-red-900/20"
                  >
                    <Trash2 class="w-4 h-4 mr-2" />
                    Eliminar
                  </DropdownMenuItem>
                </DropdownMenuContent>
              </DropdownMenu>
            </TableCell>
          </TableRow>
        </TableBody>
      </Table>
    </div>

    <!-- Pagination -->
    <div v-if="totalPages > 1" class="flex items-center justify-between">
      <div class="flex items-center space-x-2 text-sm text-gray-700 dark:text-gray-300">
        <span>Mostrando página {{ currentPage }} de {{ totalPages }}</span>
        <span>•</span>
        <span>{{ clientes.length }} cliente{{ clientes.length !== 1 ? 's' : '' }}</span>
      </div>
      
      <div class="flex items-center space-x-2">
        <!-- Per Page Selector -->
        <div class="flex items-center space-x-2">
          <span class="text-sm text-gray-600 dark:text-gray-400">Por página:</span>
          <select
            :value="perPage"
            @change="(event) => handlePerPageChange(Number((event.target as HTMLSelectElement).value))"
            class="px-2 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-800"
          >
            <option value="10">10</option>
            <option value="25">25</option>
            <option value="50">50</option>
          </select>
        </div>
        
        <!-- Page Navigation -->
        <div class="flex items-center space-x-1">
          <Button
            variant="outline"
            size="sm"
            :disabled="currentPage === 1"
            @click="handlePageChange(currentPage - 1)"
            class="px-2 py-1"
          >
            Anterior
          </Button>
          
          <div class="flex items-center space-x-1">
            <Button
              v-for="page in pageNumbers"
              :key="page"
              :variant="page === currentPage ? 'default' : 'outline'"
              size="sm"
              @click="handlePageChange(page)"
              class="px-2 py-1 min-w-[32px]"
            >
              {{ page }}
            </Button>
          </div>
          
          <Button
            variant="outline"
            size="sm"
            :disabled="currentPage === totalPages"
            @click="handlePageChange(currentPage + 1)"
            class="px-2 py-1"
          >
            Siguiente
          </Button>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.animate-pulse {
  animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

@keyframes pulse {
  0%, 100% {
    opacity: 1;
  }
  50% {
    opacity: .5;
  }
}

.animate-spin {
  animation: spin 1s linear infinite;
}

@keyframes spin {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}
</style>
