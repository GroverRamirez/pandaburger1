<script setup lang="ts">
import { ref, watch, computed } from 'vue';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select';
import { 
  Search, 
  Filter, 
  X, 
  RefreshCw, 
  Calendar,
  TrendingUp,
  User,
  Mail,
  Phone
} from 'lucide-vue-next';
import type { ClienteFilters } from '@/types/client';

interface Props {
  filters: ClienteFilters;
  loading?: boolean;
}

interface Emits {
  (e: 'update:filters', filters: ClienteFilters): void;
  (e: 'search'): void;
  (e: 'reset'): void;
}

const props = defineProps<Props>();
const emit = defineEmits<Emits>();

const localFilters = ref<ClienteFilters>({ ...props.filters });

const searchTerm = ref(props.filters.search || '');
const sortBy = ref(props.filters.sortBy || 'nombre');
const sortOrder = ref(props.filters.sortOrder || 'asc');
const statusFilter = ref<'all' | 'active' | 'inactive' | 'new' | 'vip'>('all');
const dateRange = ref<'all' | 'today' | 'week' | 'month' | 'year'>('all');
const minOrders = ref<number | ''>('');
const maxOrders = ref<number | ''>('');
const minSpent = ref<number | ''>('');
const maxSpent = ref<number | ''>('');

const hasActiveFilters = computed(() => {
  return searchTerm.value || 
         statusFilter.value !== 'all' || 
         dateRange.value !== 'all' || 
         minOrders.value !== '' || 
         maxOrders.value !== '' || 
         minSpent.value !== '' || 
         maxSpent.value !== '';
});

const activeFiltersCount = computed(() => {
  let count = 0;
  if (searchTerm.value) count++;
  if (statusFilter.value !== 'all') count++;
  if (dateRange.value !== 'all') count++;
  if (minOrders.value !== '') count++;
  if (maxOrders.value !== '') count++;
  if (minSpent.value !== '') count++;
  if (maxSpent.value !== '') count++;
  return count;
});

const updateFilters = () => {
  const newFilters: ClienteFilters = {
    search: searchTerm.value,
    sortBy: sortBy.value,
    sortOrder: sortOrder.value,
    page: 1, // Reset to first page when filters change
    perPage: props.filters.perPage || 10
  };

  // Add custom filters as additional parameters
  if (statusFilter.value !== 'all') {
    newFilters.status = statusFilter.value;
  }
  if (dateRange.value !== 'all') {
    newFilters.dateRange = dateRange.value;
  }
  if (minOrders.value !== '') {
    newFilters.minOrders = Number(minOrders.value);
  }
  if (maxOrders.value !== '') {
    newFilters.maxOrders = Number(maxOrders.value);
  }
  if (minSpent.value !== '') {
    newFilters.minSpent = Number(minSpent.value);
  }
  if (maxSpent.value !== '') {
    newFilters.maxSpent = Number(maxSpent.value);
  }

  emit('update:filters', newFilters);
};

const handleSearch = () => {
  updateFilters();
  emit('search');
};

const resetFilters = () => {
  searchTerm.value = '';
  statusFilter.value = 'all';
  dateRange.value = 'all';
  minOrders.value = '';
  maxOrders.value = '';
  minSpent.value = '';
  maxSpent.value = '';
  
  const resetFilters: ClienteFilters = {
    search: '',
    sortBy: 'nombre',
    sortOrder: 'asc',
    page: 1,
    perPage: props.filters.perPage || 10
  };
  
  emit('update:filters', resetFilters);
  emit('search');
};

const removeFilter = (filterType: string) => {
  switch (filterType) {
    case 'search':
      searchTerm.value = '';
      break;
    case 'status':
      statusFilter.value = 'all';
      break;
    case 'dateRange':
      dateRange.value = 'all';
      break;
    case 'orders':
      minOrders.value = '';
      maxOrders.value = '';
      break;
    case 'spent':
      minSpent.value = '';
      maxSpent.value = '';
      break;
  }
  updateFilters();
};

// Watch for filter changes and update parent
watch([searchTerm, sortBy, sortOrder, statusFilter, dateRange, minOrders, maxOrders, minSpent, maxSpent], () => {
  updateFilters();
}, { deep: true });

// Watch for props changes and update local state
watch(() => props.filters, (newFilters) => {
  localFilters.value = { ...newFilters };
  searchTerm.value = newFilters.search || '';
  sortBy.value = newFilters.sortBy || 'nombre';
  sortOrder.value = newFilters.sortOrder || 'asc';
}, { deep: true });
</script>

<template>
  <Card class="mb-6">
    <CardHeader>
      <CardTitle class="flex items-center space-x-2">
        <Filter class="w-5 h-5 text-orange-500" />
        <span>Filtros y Búsqueda</span>
        <Badge v-if="activeFiltersCount > 0" variant="secondary" class="ml-2">
          {{ activeFiltersCount }} activo{{ activeFiltersCount !== 1 ? 's' : '' }}
        </Badge>
      </CardTitle>
    </CardHeader>
    
    <CardContent class="space-y-6">
      <!-- Search and Basic Filters -->
      <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
        <!-- Search -->
        <div class="md:col-span-2">
          <Label for="search">Buscar Clientes</Label>
          <div class="relative mt-1">
            <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" />
            <Input
              id="search"
              v-model="searchTerm"
              placeholder="Nombre, email, teléfono..."
              class="pl-10"
              @keyup.enter="handleSearch"
            />
          </div>
        </div>
        
        <!-- Sort By -->
        <div>
          <Label for="sort">Ordenar por</Label>
          <Select v-model="sortBy">
            <SelectTrigger class="mt-1">
              <SelectValue />
            </SelectTrigger>
            <SelectContent>
              <SelectItem value="nombre">Nombre</SelectItem>
              <SelectItem value="correo_electronico">Email</SelectItem>
              <SelectItem value="created_at">Fecha de Registro</SelectItem>
              <SelectItem value="last_login_at">Último Acceso</SelectItem>
            </SelectContent>
          </Select>
        </div>

        <!-- Sort Order -->
        <div>
          <Label for="order">Orden</Label>
          <Select v-model="sortOrder">
            <SelectTrigger class="mt-1">
              <SelectValue />
            </SelectTrigger>
            <SelectContent>
              <SelectItem value="asc">Ascendente</SelectItem>
              <SelectItem value="desc">Descendente</SelectItem>
            </SelectContent>
          </Select>
        </div>
      </div>

      <!-- Advanced Filters -->
      <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
        <h4 class="font-medium text-gray-900 dark:text-white mb-4">Filtros Avanzados</h4>
        
        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
          <!-- Status Filter -->
          <div>
            <Label for="status">Estado del Cliente</Label>
            <Select v-model="statusFilter">
              <SelectTrigger class="mt-1">
                <SelectValue />
              </SelectTrigger>
              <SelectContent>
                <SelectItem value="all">Todos los Estados</SelectItem>
                <SelectItem value="active">Activos</SelectItem>
                <SelectItem value="inactive">Inactivos</SelectItem>
                <SelectItem value="new">Nuevos</SelectItem>
                <SelectItem value="vip">VIP</SelectItem>
              </SelectContent>
            </Select>
          </div>

          <!-- Date Range -->
          <div>
            <Label for="dateRange">Rango de Fechas</Label>
            <Select v-model="dateRange">
              <SelectTrigger class="mt-1">
                <SelectValue />
              </SelectTrigger>
              <SelectContent>
                <SelectItem value="all">Todas las Fechas</SelectItem>
                <SelectItem value="today">Hoy</SelectItem>
                <SelectItem value="week">Esta Semana</SelectItem>
                <SelectItem value="month">Este Mes</SelectItem>
                <SelectItem value="year">Este Año</SelectItem>
              </SelectContent>
            </Select>
          </div>

          <!-- Orders Range -->
          <div>
            <Label>Rango de Pedidos</Label>
            <div class="grid grid-cols-2 gap-2 mt-1">
              <Input
                v-model="minOrders"
                type="number"
                placeholder="Mín"
                min="0"
              />
              <Input
                v-model="maxOrders"
                type="number"
                placeholder="Máx"
                min="0"
              />
            </div>
          </div>
        </div>

        <!-- Spending Range -->
        <div class="mt-4">
          <Label>Rango de Gastos (BOB)</Label>
          <div class="grid grid-cols-2 gap-4 mt-1">
            <Input
              v-model="minSpent"
              type="number"
              placeholder="Mínimo gastado"
              min="0"
              step="0.01"
            />
            <Input
              v-model="maxSpent"
              type="number"
              placeholder="Máximo gastado"
              min="0"
              step="0.01"
            />
          </div>
        </div>
      </div>

      <!-- Active Filters Display -->
      <div v-if="hasActiveFilters" class="border-t border-gray-200 dark:border-gray-700 pt-4">
        <div class="flex items-center justify-between mb-3">
          <h5 class="font-medium text-gray-900 dark:text-white">Filtros Activos</h5>
          <Button variant="ghost" size="sm" @click="resetFilters">
            <X class="w-4 h-4 mr-2" />
            Limpiar Todo
          </Button>
        </div>
        
        <div class="flex flex-wrap gap-2">
          <Badge 
            v-if="searchTerm" 
            variant="secondary" 
            class="flex items-center space-x-1"
          >
            <Search class="w-3 h-3" />
            <span>Búsqueda: "{{ searchTerm }}"</span>
            <button @click="removeFilter('search')" class="ml-1 hover:text-red-500">
              <X class="w-3 h-3" />
            </button>
          </Badge>

          <Badge 
            v-if="statusFilter !== 'all'" 
            variant="secondary" 
            class="flex items-center space-x-1"
          >
            <User class="w-3 h-3" />
            <span>Estado: {{ statusFilter }}</span>
            <button @click="removeFilter('status')" class="ml-1 hover:text-red-500">
              <X class="w-3 h-3" />
            </button>
          </Badge>

          <Badge 
            v-if="dateRange !== 'all'" 
            variant="secondary" 
            class="flex items-center space-x-1"
          >
            <Calendar class="w-3 h-3" />
            <span>Fecha: {{ dateRange }}</span>
            <button @click="removeFilter('dateRange')" class="ml-1 hover:text-red-500">
              <X class="w-3 h-3" />
            </button>
          </Badge>

          <Badge 
            v-if="minOrders !== '' || maxOrders !== ''" 
            variant="secondary" 
            class="flex items-center space-x-1"
          >
            <TrendingUp class="w-3 h-3" />
            <span>
              Pedidos: 
              {{ minOrders !== '' ? minOrders : '0' }} - 
              {{ maxOrders !== '' ? maxOrders : '∞' }}
            </span>
            <button @click="removeFilter('orders')" class="ml-1 hover:text-red-500">
              <X class="w-3 h-3" />
            </button>
          </Badge>

          <Badge 
            v-if="minSpent !== '' || maxSpent !== ''" 
            variant="secondary" 
            class="flex items-center space-x-1"
          >
            <span class="text-xs">💰</span>
            <span>
              Gasto: 
              {{ minSpent !== '' ? minSpent : '0' }} - 
              {{ maxSpent !== '' ? maxSpent : '∞' }} BOB
            </span>
            <button @click="removeFilter('spent')" class="ml-1 hover:text-red-500">
              <X class="w-3 h-3" />
            </button>
          </Badge>
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-700">
        <div class="flex items-center space-x-3">
          <Button 
            @click="handleSearch" 
            :disabled="props.loading"
            class="bg-gradient-to-r from-orange-500 to-red-500 hover:from-orange-600 hover:to-red-600 text-white"
          >
            <Search class="w-4 h-4 mr-2" />
            {{ props.loading ? 'Buscando...' : 'Buscar' }}
          </Button>
          
          <Button 
            variant="outline" 
            @click="resetFilters"
            :disabled="props.loading"
          >
            <RefreshCw class="w-4 h-4 mr-2" />
            Restablecer
          </Button>
        </div>

        <div class="text-sm text-gray-500">
          Los filtros se aplican automáticamente
        </div>
      </div>
    </CardContent>
  </Card>
</template>
