<script lang="ts">
import { defineComponent, ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { clientService } from '@/services/clientService';
import type { Cliente, ClienteOrder } from '@/types/client';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Badge } from '@/components/ui/badge';
import { useToast } from '@/components/ui/toast/use-toast';
import { Loader2, Edit, ArrowLeft, Calendar, Phone, Mail, MapPin, Clock } from 'lucide-vue-next';

export default defineComponent({
  name: 'ClientProfile',

  setup() {
    const route = useRoute();
    const router = useRouter();
    const { toast } = useToast();

    const loading = ref(true);
    const loadingOrders = ref(true);
    const client = ref<Cliente | null>(null);
    const orders = ref<ClienteOrder[]>([]);
    const error = ref<string | null>(null);

    const fetchClient = async () => {
  try {
    loading.value = true;
    client.value = await clientService.getClient(Number(route.params.id));
    await fetchClientOrders();
  } catch (err) {
    error.value = 'Error al cargar los datos del cliente';
    console.error('Error fetching client:', err);
  } finally {
    loading.value = false;
  }
};

    const fetchClientOrders = async () => {
  try {
    loadingOrders.value = true;
    orders.value = await clientService.getClientOrders(Number(route.params.id));
  } catch (err) {
    console.error('Error fetching client orders:', err);
  } finally {
    loadingOrders.value = false;
  }
};

    const formatDate = (dateString: string) => {
  return new Date(dateString).toLocaleDateString('es-BO', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  });
};

    const formatCurrency = (amount: number) => {
  return new Intl.NumberFormat('es-BO', {
    style: 'currency',
    currency: 'BOB',
    minimumFractionDigits: 2
  }).format(amount);
};

    const getStatusBadgeVariant = (status: string) => {
  const statusMap: Record<string, string> = {
    'pendiente': 'warning',
    'en_proceso': 'info',
    'completado': 'success',
    'cancelado': 'destructive'
  };
  return statusMap[status.toLowerCase()] || 'default';
};

    onMounted(() => {
      fetchClient();
    });

    return {
      loading,
      loadingOrders,
      client,
      orders,
      error,
      router,
      route,
      fetchClient,
      fetchClientOrders,
      formatDate,
      formatCurrency,
      getStatusBadgeVariant,
      toast
    };
  }
});
</script>

<template>
  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <div>
        <Button variant="ghost" size="icon" class="mr-2" @click="router.go(-1)">
          <ArrowLeft class="h-4 w-4" />
        </Button>
        <span class="text-2xl font-bold">Perfil del Cliente</span>
      </div>
      <Button 
        v-if="client" 
        @click="router.push({ name: 'clientes.edit', params: { id: client.id.toString() } })"
      >
        <Edit class="w-4 h-4 mr-2" />
        Editar Cliente
      </Button>
    </div>

    <div v-if="loading" class="flex justify-center p-8">
      <Loader2 class="h-8 w-8 animate-spin" />
    </div>
    
    <div v-else-if="error" class="text-center p-8 text-destructive">
      {{ error }}
    </div>
    
    <div v-else-if="client" class="grid gap-6">
      <!-- Client Info Card -->
      <Card>
        <CardHeader>
          <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
              <CardTitle class="text-2xl">{{ client.nombre }}</CardTitle>
              <CardDescription>Cliente desde {{ new Date(client.created_at).toLocaleDateString() }}</CardDescription>
            </div>
            <div class="flex items-center space-x-2">
              <Badge variant="outline" class="text-sm">
                ID: {{ client.id }}
              </Badge>
              <Badge v-if="client.last_login_at" variant="secondary">
                Último acceso: {{ formatDate(client.last_login_at) }}
              </Badge>
            </div>
          </div>
        </CardHeader>
        <CardContent>
          <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
            <div class="flex items-start space-x-4">
              <div class="p-2 bg-primary/10 rounded-full">
                <Mail class="h-5 w-5 text-primary" />
              </div>
              <div>
                <p class="text-sm font-medium text-muted-foreground">Correo electrónico</p>
                <p class="text-sm">{{ client.correo_electronico || 'No especificado' }}</p>
              </div>
            </div>
            
            <div class="flex items-start space-x-4">
              <div class="p-2 bg-primary/10 rounded-full">
                <Phone class="h-5 w-5 text-primary" />
              </div>
              <div>
                <p class="text-sm font-medium text-muted-foreground">Teléfono</p>
                <p class="text-sm">{{ client.telefono || 'No especificado' }}</p>
              </div>
            </div>
            
            <div class="flex items-start space-x-4">
              <div class="p-2 bg-primary/10 rounded-full">
                <MapPin class="h-5 w-5 text-primary" />
              </div>
              <div>
                <p class="text-sm font-medium text-muted-foreground">Dirección</p>
                <p class="text-sm">{{ client.direccion || 'No especificada' }}</p>
              </div>
            </div>
            
            <div class="flex items-start space-x-4">
              <div class="p-2 bg-primary/10 rounded-full">
                <Calendar class="h-5 w-5 text-primary" />
              </div>
              <div>
                <p class="text-sm font-medium text-muted-foreground">Registrado el</p>
                <p class="text-sm">{{ formatDate(client.created_at) }}</p>
              </div>
            </div>
          </div>
        </CardContent>
      </Card>
      
      <!-- Orders History -->
      <Card>
        <CardHeader>
          <CardTitle>Historial de Pedidos</CardTitle>
          <CardDescription>Pedidos realizados por este cliente</CardDescription>
        </CardHeader>
        <CardContent>
          <div v-if="loadingOrders" class="flex justify-center p-8">
            <Loader2 class="h-8 w-8 animate-spin" />
          </div>
          
          <div v-else-if="orders.length === 0" class="text-center p-8 text-muted-foreground">
            No hay pedidos registrados para este cliente.
          </div>
          
          <div v-else class="rounded-md border">
            <Table>
              <TableHeader>
                <TableRow>
                  <TableHead>ID</TableHead>
                  <TableHead>Estado</TableHead>
                  <TableHead>Fecha</TableHead>
                  <TableHead class="text-right">Total</TableHead>
                  <TableHead class="w-[100px]"></TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                <TableRow v-for="order in orders" :key="order.id">
                  <TableCell class="font-medium">#{{ order.id }}</TableCell>
                  <TableCell>
                    <Badge :variant="getStatusBadgeVariant(order.estado.nombre)">
                      {{ order.estado.nombre }}
                    </Badge>
                  </TableCell>
                  <TableCell>
                    <div class="flex items-center">
                      <Calendar class="mr-2 h-4 w-4 opacity-70" />
                      <span>{{ formatDate(order.created_at) }}</span>
                    </div>
                  </TableCell>
                  <td class="text-right font-medium">
                    {{ formatCurrency(order.total) }}
                  </td>
                  <td>
                    <Button variant="ghost" size="sm" class="h-8 w-8 p-0">
                      <a :href="`/pedidos/${order.id}" class="w-full h-full flex items-center justify-center">
                        <svg
                          xmlns="http://www.w3.org/2000/svg"
                          width="24"
                          height="24"
                          viewBox="0 0 24 24"
                          fill="none"
                          stroke="currentColor"
                          stroke-width="2"
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          class="h-4 w-4"
                        >
                          <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path>
                        </svg>
                      </a>
                    </Button>
                  </td>
                </TableRow>
              </TableBody>
            </Table>
          </div>
        </CardContent>
      </Card>
    </div>
  </div>
</template>
