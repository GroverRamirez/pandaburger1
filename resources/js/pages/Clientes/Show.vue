<script setup lang="ts">
import { ref, onMounted, computed } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import { Progress } from '@/components/ui/progress';
import { Link } from '@inertiajs/vue3';
import { 
  ArrowLeft, 
  Mail, 
  Phone, 
  MapPin, 
  Calendar, 
  Clock, 
  TrendingUp, 
  Star,
  Package,
  DollarSign,
  Activity,
  User,
  ShoppingCart,
  Heart,
  Award,
  Target,
  Edit
} from 'lucide-vue-next';
import { clientService } from '@/services/clientService';
import type { ClienteOrder } from '@/types/client';

interface Pedido {
  id: number;
  fecha_pedido: string;
  estado: {
    id: number;
    nombre: string;
    color?: string;
  };
  total: number;
  items_count?: number;
}

interface Cliente {
  id: number;
  nombre: string;
  correo_electronico: string;
  telefono: string;
  direccion: string;
  created_at: string;
  last_login_at?: string;
  pedidos: Pedido[];
}

const props = defineProps<{ cliente: Cliente }>();

const activeTab = ref('overview');
const isLoading = ref(false);

const formatCurrency = (value: number) => {
  return new Intl.NumberFormat('es-BO', { style: 'currency', currency: 'BOB' }).format(value);
};

const formatDate = (dateString: string) => {
  const options: Intl.DateTimeFormatOptions = { 
    year: 'numeric', 
    month: 'long', 
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  };
  return new Date(dateString).toLocaleDateString('es-ES', options);
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

const clienteStats = computed(() => {
  const totalOrders = props.cliente.pedidos.length;
  const totalSpent = props.cliente.pedidos.reduce((sum, pedido) => sum + pedido.total, 0);
  const averageOrder = totalOrders > 0 ? totalSpent / totalOrders : 0;
  const lastOrder = props.cliente.pedidos.length > 0 
    ? new Date(Math.max(...props.cliente.pedidos.map(p => new Date(p.fecha_pedido).getTime())))
    : null;
  
  const daysSinceLastOrder = lastOrder 
    ? Math.ceil((new Date().getTime() - lastOrder.getTime()) / (1000 * 60 * 60 * 24))
    : null;

  return {
    totalOrders,
    totalSpent,
    averageOrder,
    lastOrder,
    daysSinceLastOrder,
    customerSince: formatRelativeDate(props.cliente.created_at),
    memberFor: Math.ceil((new Date().getTime() - new Date(props.cliente.created_at).getTime()) / (1000 * 60 * 60 * 24))
  };
});

const getStatusColor = (status: string) => {
  const colors: Record<string, string> = {
    'Pendiente': 'bg-yellow-100 text-yellow-800 border-yellow-200',
    'En Proceso': 'bg-blue-100 text-blue-800 border-blue-200',
    'Completado': 'bg-green-100 text-green-800 border-green-200',
    'Cancelado': 'bg-red-100 text-red-800 border-red-200',
    'Entregado': 'bg-purple-100 text-purple-800 border-purple-200'
  };
  return colors[status] || 'bg-gray-100 text-gray-800 border-gray-200';
};

const getCustomerTier = computed(() => {
  const totalSpent = clienteStats.value.totalSpent;
  if (totalSpent >= 1000) return { name: 'Cliente VIP', color: 'from-purple-500 to-pink-500', icon: Award };
  if (totalSpent >= 500) return { name: 'Cliente Frecuente', color: 'from-blue-500 to-cyan-500', icon: Star };
  if (totalSpent >= 200) return { name: 'Cliente Regular', color: 'from-green-500 to-emerald-500', icon: Heart };
  return { name: 'Cliente Nuevo', color: 'from-gray-500 to-slate-500', icon: User };
});

const recentOrders = computed(() => {
  return [...props.cliente.pedidos]
    .sort((a, b) => new Date(b.fecha_pedido).getTime() - new Date(a.fecha_pedido).getTime())
    .slice(0, 5);
});

const orderTrends = computed(() => {
  const monthlyData = props.cliente.pedidos.reduce((acc, pedido) => {
    const month = new Date(pedido.fecha_pedido).toLocaleDateString('es-ES', { month: 'short', year: 'numeric' });
    if (!acc[month]) acc[month] = { count: 0, total: 0 };
    acc[month].count++;
    acc[month].total += pedido.total;
    return acc;
  }, {} as Record<string, { count: number; total: number }>);
  
  return Object.entries(monthlyData).map(([month, data]) => ({
    month,
    count: data.count,
    total: data.total
  }));
});
</script>

<template>
  <AppLayout>
    <template #header>
      <div class="flex items-center">
        <Link :href="route('clientes.index')">
          <Button variant="outline" size="icon" class="mr-4">
            <ArrowLeft class="w-4 h-4" />
          </Button>
        </Link>
        <div>
          <h2 class="text-2xl font-bold leading-tight text-gray-900 dark:text-white">
            Perfil del Cliente
          </h2>
          <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            Información detallada y historial completo
          </p>
        </div>
      </div>
    </template>

    <div class="py-8">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <!-- Customer Header Card -->
        <Card class="mb-8 bg-gradient-to-r from-orange-50 to-red-50 dark:from-orange-900/20 dark:to-red-900/20 border-orange-200 dark:border-orange-700">
          <CardContent class="p-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
              <div class="flex items-center space-x-6">
                <div class="w-20 h-20 bg-gradient-to-br from-orange-400 to-red-500 rounded-full flex items-center justify-center text-white text-2xl font-bold">
                  {{ cliente.nombre.charAt(0).toUpperCase() }}
                </div>
                <div>
                  <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ cliente.nombre }}</h1>
                  <div class="flex items-center space-x-4 mt-2">
                    <Badge :class="`bg-gradient-to-r ${getCustomerTier.color} text-white border-0`">
                      <component :is="getCustomerTier.icon" class="w-3 h-3 mr-1" />
                      {{ getCustomerTier.name }}
                    </Badge>
                    <span class="text-sm text-gray-600 dark:text-gray-400">
                      Cliente desde {{ clienteStats.customerSince }}
                    </span>
                  </div>
                </div>
              </div>
              <div class="mt-6 lg:mt-0 lg:text-right">
                <div class="grid grid-cols-2 gap-4 text-center">
                  <div>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ clienteStats.totalOrders }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Pedidos Totales</p>
                  </div>
                  <div>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ formatCurrency(clienteStats.totalSpent) }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Total Gastado</p>
                  </div>
                </div>
              </div>
            </div>
          </CardContent>
        </Card>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 gap-6 mb-8 sm:grid-cols-2 lg:grid-cols-4">
          <Card class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 border-blue-200 dark:border-blue-700">
            <CardContent class="p-6">
              <div class="flex items-center">
                <div class="p-2 bg-blue-500 rounded-lg">
                  <ShoppingCart class="w-6 h-6 text-white" />
                </div>
                <div class="ml-4">
                  <p class="text-sm font-medium text-blue-600 dark:text-blue-400">Total Pedidos</p>
                  <p class="text-2xl font-bold text-blue-900 dark:text-blue-100">{{ clienteStats.totalOrders }}</p>
                </div>
              </div>
            </CardContent>
          </Card>

          <Card class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 border-green-200 dark:border-green-700">
            <CardContent class="p-6">
              <div class="flex items-center">
                <div class="p-2 bg-green-500 rounded-lg">
                  <DollarSign class="w-6 h-6 text-white" />
                </div>
                <div class="ml-4">
                  <p class="text-sm font-medium text-green-600 dark:text-green-400">Promedio por Pedido</p>
                  <p class="text-2xl font-bold text-green-900 dark:text-green-100">{{ formatCurrency(clienteStats.averageOrder) }}</p>
                </div>
              </div>
            </CardContent>
          </Card>

          <Card class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 border-purple-200 dark:border-purple-700">
            <CardContent class="p-6">
              <div class="flex items-center">
                <div class="p-2 bg-purple-500 rounded-lg">
                  <Clock class="w-6 h-6 text-white" />
                </div>
                <div class="ml-4">
                  <p class="text-sm font-medium text-purple-600 dark:text-purple-400">Último Pedido</p>
                  <p class="text-lg font-bold text-purple-900 dark:text-purple-100">
                    {{ clienteStats.daysSinceLastOrder ? `${clienteStats.daysSinceLastOrder} días` : 'Nunca' }}
                  </p>
                </div>
              </div>
            </CardContent>
          </Card>

          <Card class="bg-gradient-to-br from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20 border-orange-200 dark:border-orange-700">
            <CardContent class="p-6">
              <div class="flex items-center">
                <div class="p-2 bg-orange-500 rounded-lg">
                  <Target class="w-6 h-6 text-white" />
                </div>
                <div class="ml-4">
                  <p class="text-sm font-medium text-orange-600 dark:text-orange-400">Valor Total</p>
                  <p class="text-2xl font-bold text-orange-900 dark:text-orange-100">{{ formatCurrency(clienteStats.totalSpent) }}</p>
                </div>
              </div>
            </CardContent>
          </Card>
        </div>

        <!-- Main Content Tabs -->
        <Tabs v-model="activeTab" class="space-y-6">
          <TabsList class="grid w-full grid-cols-4">
            <TabsTrigger value="overview">Resumen</TabsTrigger>
            <TabsTrigger value="orders">Pedidos</TabsTrigger>
            <TabsTrigger value="analytics">Analíticas</TabsTrigger>
            <TabsTrigger value="profile">Perfil</TabsTrigger>
          </TabsList>

          <!-- Overview Tab -->
          <TabsContent value="overview" class="space-y-6">
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
              <!-- Recent Orders -->
              <Card>
                <CardHeader>
                  <CardTitle class="flex items-center space-x-2">
                    <Package class="w-5 h-5" />
                    <span>Pedidos Recientes</span>
                  </CardTitle>
                </CardHeader>
                <CardContent>
                  <div class="space-y-4">
                    <div v-if="recentOrders.length === 0" class="text-center py-8 text-gray-500">
                      <Package class="w-12 h-12 mx-auto mb-4 text-gray-300" />
                      <p>No hay pedidos recientes</p>
                    </div>
                    <div v-for="pedido in recentOrders" :key="pedido.id" class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                      <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-orange-100 dark:bg-orange-900/20 rounded-full flex items-center justify-center">
                          <Package class="w-4 h-4 text-orange-600" />
                        </div>
                        <div>
                          <p class="font-medium">Pedido #{{ pedido.id }}</p>
                          <p class="text-sm text-gray-500">{{ formatDate(pedido.fecha_pedido) }}</p>
                        </div>
                      </div>
                      <div class="text-right">
                        <p class="font-semibold">{{ formatCurrency(pedido.total) }}</p>
                        <Badge :class="getStatusColor(pedido.estado.nombre)" class="text-xs">
                          {{ pedido.estado.nombre }}
                        </Badge>
                      </div>
                    </div>
                  </div>
                </CardContent>
              </Card>

              <!-- Customer Insights -->
              <Card>
                <CardHeader>
                  <CardTitle class="flex items-center space-x-2">
                    <TrendingUp class="w-5 h-5" />
                    <span>Insights del Cliente</span>
                  </CardTitle>
                </CardHeader>
                <CardContent>
                  <div class="space-y-6">
                    <div>
                      <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium">Frecuencia de Pedidos</span>
                        <span class="text-sm text-gray-500">{{ clienteStats.memberFor }} días</span>
                      </div>
                      <Progress 
                        :value="Math.min((clienteStats.totalOrders / Math.max(clienteStats.memberFor / 30, 1)) * 100, 100)" 
                        class="h-2"
                      />
                      <p class="text-xs text-gray-500 mt-1">
                        {{ clienteStats.totalOrders }} pedidos en {{ Math.ceil(clienteStats.memberFor / 30) }} meses
                      </p>
                    </div>

                    <div>
                      <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium">Valor Promedio</span>
                        <span class="text-sm text-gray-500">{{ formatCurrency(clienteStats.averageOrder) }}</span>
                      </div>
                      <Progress 
                        :value="Math.min((clienteStats.averageOrder / 100) * 100, 100)" 
                        class="h-2"
                      />
                      <p class="text-xs text-gray-500 mt-1">
                        Promedio por pedido
                      </p>
                    </div>

                    <div class="pt-4 border-t">
                      <h4 class="font-medium mb-3">Tendencia Mensual</h4>
                      <div class="space-y-2">
                        <div v-for="trend in orderTrends.slice(-3)" :key="trend.month" class="flex items-center justify-between text-sm">
                          <span>{{ trend.month }}</span>
                          <div class="flex items-center space-x-2">
                            <span>{{ trend.count }} pedidos</span>
                            <span class="text-gray-500">{{ formatCurrency(trend.total) }}</span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </CardContent>
              </Card>
            </div>
          </TabsContent>

          <!-- Orders Tab -->
          <TabsContent value="orders" class="space-y-6">
            <Card>
              <CardHeader>
                <CardTitle class="flex items-center space-x-2">
                  <ShoppingCart class="w-5 h-5" />
                  <span>Historial Completo de Pedidos</span>
                  <Badge variant="secondary">{{ cliente.pedidos.length }} pedidos</Badge>
                </CardTitle>
              </CardHeader>
              <CardContent>
                <div class="overflow-x-auto">
                  <Table>
                    <TableHeader>
                      <TableRow class="bg-gray-50 dark:bg-gray-800">
                        <TableHead>ID Pedido</TableHead>
                        <TableHead>Fecha</TableHead>
                        <TableHead>Estado</TableHead>
                        <TableHead>Total</TableHead>
                        <TableHead class="text-right">Acciones</TableHead>
                      </TableRow>
                    </TableHeader>
                    <TableBody>
                      <TableRow v-if="cliente.pedidos.length === 0" class="hover:bg-transparent">
                        <TableCell colspan="5" class="text-center py-12">
                          <div class="text-gray-500 dark:text-gray-400">
                            <ShoppingCart class="w-12 h-12 mx-auto mb-4 text-gray-300" />
                            <p class="text-lg font-medium">No hay pedidos</p>
                            <p class="text-sm">Este cliente aún no ha realizado pedidos</p>
                          </div>
                        </TableCell>
                      </TableRow>
                      <TableRow 
                        v-for="pedido in cliente.pedidos" 
                        :key="pedido.id"
                        class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors"
                      >
                        <TableCell>
                          <div class="flex items-center space-x-2">
                            <div class="w-8 h-8 bg-orange-100 dark:bg-orange-900/20 rounded-full flex items-center justify-center">
                              <Package class="w-4 h-4 text-orange-600" />
                            </div>
                            <span class="font-medium">#{{ pedido.id }}</span>
                          </div>
                        </TableCell>
                        <TableCell>
                          <div class="space-y-1">
                            <p class="font-medium">{{ formatDate(pedido.fecha_pedido) }}</p>
                            <p class="text-sm text-gray-500">{{ formatRelativeDate(pedido.fecha_pedido) }}</p>
                          </div>
                        </TableCell>
                        <TableCell>
                          <Badge :class="getStatusColor(pedido.estado.nombre)">
                            {{ pedido.estado.nombre }}
                          </Badge>
                        </TableCell>
                        <TableCell>
                          <div class="font-semibold text-lg">{{ formatCurrency(pedido.total) }}</div>
                          <div v-if="pedido.items_count" class="text-sm text-gray-500">
                            {{ pedido.items_count }} items
                          </div>
                        </TableCell>
                        <TableCell class="text-right">
                          <Button variant="outline" size="sm">
                            Ver Detalles
                          </Button>
                        </TableCell>
                      </TableRow>
                    </TableBody>
                  </Table>
                </div>
              </CardContent>
            </Card>
          </TabsContent>

          <!-- Analytics Tab -->
          <TabsContent value="analytics" class="space-y-6">
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
              <!-- Order Frequency Chart -->
              <Card>
                <CardHeader>
                  <CardTitle class="flex items-center space-x-2">
                    <Activity class="w-5 h-5" />
                    <span>Frecuencia de Pedidos</span>
                  </CardTitle>
                </CardHeader>
                <CardContent>
                  <div class="space-y-4">
                    <div v-for="trend in orderTrends" :key="trend.month" class="space-y-2">
                      <div class="flex items-center justify-between">
                        <span class="text-sm font-medium">{{ trend.month }}</span>
                        <span class="text-sm text-gray-500">{{ trend.count }} pedidos</span>
                      </div>
                      <Progress :value="(trend.count / Math.max(...orderTrends.map(t => t.count))) * 100" class="h-2" />
                    </div>
                  </div>
                </CardContent>
              </Card>

              <!-- Spending Analysis -->
              <Card>
                <CardHeader>
                  <CardTitle class="flex items-center space-x-2">
                    <DollarSign class="w-5 h-5" />
                    <span>Análisis de Gastos</span>
                  </CardTitle>
                </CardHeader>
                <CardContent>
                  <div class="space-y-6">
                    <div class="text-center">
                      <p class="text-3xl font-bold text-gray-900 dark:text-white">
                        {{ formatCurrency(clienteStats.totalSpent) }}
                      </p>
                      <p class="text-sm text-gray-500">Total gastado</p>
                    </div>
                    
                    <div class="space-y-4">
                      <div>
                        <div class="flex items-center justify-between mb-2">
                          <span class="text-sm">Promedio por pedido</span>
                          <span class="text-sm font-medium">{{ formatCurrency(clienteStats.averageOrder) }}</span>
                        </div>
                        <Progress :value="(clienteStats.averageOrder / 100) * 100" class="h-2" />
                      </div>
                      
                      <div>
                        <div class="flex items-center justify-between mb-2">
                          <span class="text-sm">Pedido más alto</span>
                          <span class="text-sm font-medium">
                            {{ formatCurrency(Math.max(...cliente.pedidos.map(p => p.total))) }}
                          </span>
                        </div>
                        <Progress :value="100" class="h-2" />
                      </div>
                    </div>
                  </div>
                </CardContent>
              </Card>
            </div>
          </TabsContent>

          <!-- Profile Tab -->
          <TabsContent value="profile" class="space-y-6">
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
              <!-- Personal Information -->
              <Card>
                <CardHeader>
                  <CardTitle class="flex items-center space-x-2">
                    <User class="w-5 h-5" />
                    <span>Información Personal</span>
                  </CardTitle>
                </CardHeader>
                <CardContent class="space-y-4">
                  <div class="grid grid-cols-1 gap-4">
                    <div class="flex items-center space-x-3 p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                      <User class="w-5 h-5 text-gray-400" />
                      <div>
                        <p class="text-sm text-gray-500">Nombre</p>
                        <p class="font-medium">{{ cliente.nombre }}</p>
                      </div>
                    </div>
                    
                    <div v-if="cliente.correo_electronico" class="flex items-center space-x-3 p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                      <Mail class="w-5 h-5 text-gray-400" />
                      <div>
                        <p class="text-sm text-gray-500">Email</p>
                        <p class="font-medium">{{ cliente.correo_electronico }}</p>
                      </div>
                    </div>
                    
                    <div v-if="cliente.telefono" class="flex items-center space-x-3 p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                      <Phone class="w-5 h-5 text-gray-400" />
                      <div>
                        <p class="text-sm text-gray-500">Teléfono</p>
                        <p class="font-medium">{{ cliente.telefono }}</p>
                      </div>
                    </div>
                    
                    <div v-if="cliente.direccion" class="flex items-center space-x-3 p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                      <MapPin class="w-5 h-5 text-gray-400" />
                      <div>
                        <p class="text-sm text-gray-500">Dirección</p>
                        <p class="font-medium">{{ cliente.direccion }}</p>
                      </div>
                    </div>
                  </div>
                </CardContent>
              </Card>

              <!-- Account Information -->
              <Card>
                <CardHeader>
                  <CardTitle class="flex items-center space-x-2">
                    <Calendar class="w-5 h-5" />
                    <span>Información de Cuenta</span>
                  </CardTitle>
                </CardHeader>
                <CardContent class="space-y-4">
                  <div class="grid grid-cols-1 gap-4">
                    <div class="flex items-center space-x-3 p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                      <Calendar class="w-5 h-5 text-gray-400" />
                      <div>
                        <p class="text-sm text-gray-500">Fecha de Registro</p>
                        <p class="font-medium">{{ formatDate(cliente.created_at) }}</p>
                      </div>
                    </div>
                    
                    <div class="flex items-center space-x-3 p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                      <Clock class="w-5 h-5 text-gray-400" />
                      <div>
                        <p class="text-sm text-gray-500">Cliente desde</p>
                        <p class="font-medium">{{ clienteStats.customerSince }}</p>
                      </div>
                    </div>
                    
                    <div v-if="cliente.last_login_at" class="flex items-center space-x-3 p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                      <Activity class="w-5 h-5 text-gray-400" />
                      <div>
                        <p class="text-sm text-gray-500">Último Acceso</p>
                        <p class="font-medium">{{ formatDate(cliente.last_login_at) }}</p>
                      </div>
                    </div>
                  </div>
                  
                  <div class="pt-4 border-t">
                    <Link :href="route('clientes.edit', cliente.id)">
                      <Button class="w-full">
                        <Edit class="w-4 h-4 mr-2" />
                        Editar Cliente
                      </Button>
                    </Link>
                  </div>
                </CardContent>
              </Card>
            </div>
          </TabsContent>
        </Tabs>
      </div>
    </div>
  </AppLayout>
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
</style>