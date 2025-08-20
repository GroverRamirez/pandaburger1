<script setup lang="ts">
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { 
  Users, 
  TrendingUp, 
  Clock, 
  Star, 
  UserPlus, 
  ShoppingCart,
  DollarSign,
  Activity
} from 'lucide-vue-next';

interface ClientStats {
  total: number;
  active: number;
  newThisMonth: number;
  totalOrders: number;
  averageOrderValue: number;
  growthRate: number;
  topSpenders: number;
}

interface Props {
  stats: ClientStats;
  loading?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
  loading: false
});

const formatCurrency = (value: number) => {
  return new Intl.NumberFormat('es-BO', { style: 'currency', currency: 'BOB' }).format(value);
};

const formatNumber = (value: number) => {
  return new Intl.NumberFormat('es-BO').format(value);
};

const getGrowthColor = (rate: number) => {
  if (rate > 0) return 'text-green-600 bg-green-100 dark:bg-green-900/20';
  if (rate < 0) return 'text-red-600 bg-red-100 dark:bg-red-900/20';
  return 'text-gray-600 bg-gray-100 dark:bg-gray-900/20';
};

const getGrowthIcon = (rate: number) => {
  if (rate > 0) return TrendingUp;
  if (rate < 0) return Activity;
  return Clock;
};
</script>

<template>
  <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
    <!-- Total Clientes -->
    <Card class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 border-blue-200 dark:border-blue-700">
      <CardContent class="p-6">
        <div class="flex items-center">
          <div class="p-2 bg-blue-500 rounded-lg">
            <Users class="w-6 h-6 text-white" />
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-blue-600 dark:text-blue-400">Total Clientes</p>
            <p class="text-2xl font-bold text-blue-900 dark:text-blue-100">
              {{ loading ? '...' : formatNumber(stats.total) }}
            </p>
            <div v-if="!loading" class="flex items-center mt-1">
              <Badge :class="getGrowthColor(stats.growthRate)" class="text-xs">
                <component :is="getGrowthIcon(stats.growthRate)" class="w-3 h-3 mr-1" />
                {{ stats.growthRate > 0 ? '+' : '' }}{{ stats.growthRate }}%
              </Badge>
            </div>
          </div>
        </div>
      </CardContent>
    </Card>

    <!-- Clientes Activos -->
    <Card class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 border-green-200 dark:border-green-700">
      <CardContent class="p-6">
        <div class="flex items-center">
          <div class="p-2 bg-green-500 rounded-lg">
            <TrendingUp class="w-6 h-6 text-white" />
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-green-600 dark:text-green-400">Clientes Activos</p>
            <p class="text-2xl font-bold text-green-900 dark:text-green-100">
              {{ loading ? '...' : formatNumber(stats.active) }}
            </p>
            <p class="text-xs text-green-600 dark:text-green-400 mt-1">
              {{ stats.total > 0 ? Math.round((stats.active / stats.total) * 100) : 0 }}% del total
            </p>
          </div>
        </div>
      </CardContent>
    </Card>

    <!-- Nuevos este Mes -->
    <Card class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 border-purple-200 dark:border-purple-700">
      <CardContent class="p-6">
        <div class="flex items-center">
          <div class="p-2 bg-purple-500 rounded-lg">
            <UserPlus class="w-6 h-6 text-white" />
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-purple-600 dark:text-purple-400">Nuevos este Mes</p>
            <p class="text-2xl font-bold text-purple-900 dark:text-purple-100">
              {{ loading ? '...' : formatNumber(stats.newThisMonth) }}
            </p>
            <p class="text-xs text-purple-600 dark:text-purple-400 mt-1">
              {{ stats.total > 0 ? Math.round((stats.newThisMonth / stats.total) * 100) : 0 }}% del total
            </p>
          </div>
        </div>
      </CardContent>
    </Card>

    <!-- Pedidos Totales -->
    <Card class="bg-gradient-to-br from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20 border-orange-200 dark:border-orange-700">
      <CardContent class="p-6">
        <div class="flex items-center">
          <div class="p-2 bg-orange-500 rounded-lg">
            <ShoppingCart class="w-6 h-6 text-white" />
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-orange-600 dark:text-orange-400">Pedidos Totales</p>
            <p class="text-2xl font-bold text-orange-900 dark:text-orange-100">
              {{ loading ? '...' : formatNumber(stats.totalOrders) }}
            </p>
            <p class="text-xs text-orange-600 dark:text-orange-400 mt-1">
              Promedio: {{ stats.total > 0 ? Math.round(stats.totalOrders / stats.total) : 0 }} por cliente
            </p>
          </div>
        </div>
      </CardContent>
    </Card>
  </div>

  <!-- Additional Stats Row -->
  <div class="grid grid-cols-1 gap-6 mt-6 lg:grid-cols-3">
    <!-- Valor Promedio por Pedido -->
    <Card class="bg-gradient-to-br from-cyan-50 to-cyan-100 dark:from-cyan-900/20 dark:to-cyan-800/20 border-cyan-200 dark:border-cyan-700">
      <CardContent class="p-6">
        <div class="flex items-center">
          <div class="p-2 bg-cyan-500 rounded-lg">
            <DollarSign class="w-6 h-6 text-white" />
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-cyan-600 dark:text-cyan-400">Valor Promedio</p>
            <p class="text-xl font-bold text-cyan-900 dark:text-cyan-100">
              {{ loading ? '...' : formatCurrency(stats.averageOrderValue) }}
            </p>
            <p class="text-xs text-cyan-600 dark:text-cyan-400 mt-1">
              Por pedido
            </p>
          </div>
        </div>
      </CardContent>
    </Card>

    <!-- Clientes VIP -->
    <Card class="bg-gradient-to-br from-pink-50 to-pink-100 dark:from-pink-900/20 dark:to-pink-800/20 border-pink-200 dark:border-pink-700">
      <CardContent class="p-6">
        <div class="flex items-center">
          <div class="p-2 bg-pink-500 rounded-lg">
            <Star class="w-6 h-6 text-white" />
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-pink-600 dark:text-pink-400">Clientes VIP</p>
            <p class="text-xl font-bold text-pink-900 dark:text-pink-100">
              {{ loading ? '...' : formatNumber(stats.topSpenders) }}
            </p>
            <p class="text-xs text-pink-600 dark:text-pink-400 mt-1">
              Alto valor
            </p>
          </div>
        </div>
      </CardContent>
    </Card>

    <!-- Tasa de Crecimiento -->
    <Card class="bg-gradient-to-br from-indigo-50 to-indigo-100 dark:from-indigo-900/20 dark:to-indigo-800/20 border-indigo-200 dark:border-indigo-700">
      <CardContent class="p-6">
        <div class="flex items-center">
          <div class="p-2 bg-indigo-500 rounded-lg">
            <Activity class="w-6 h-6 text-white" />
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-indigo-600 dark:text-indigo-400">Crecimiento</p>
            <p class="text-xl font-bold text-indigo-900 dark:text-indigo-100">
              {{ loading ? '...' : `${stats.growthRate > 0 ? '+' : ''}${stats.growthRate}%` }}
            </p>
            <p class="text-xs text-indigo-600 dark:text-indigo-400 mt-1">
              Este mes
            </p>
          </div>
        </div>
      </CardContent>
    </Card>
  </div>
</template>
