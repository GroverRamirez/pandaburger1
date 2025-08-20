<script lang="ts">
import { defineComponent } from 'vue';
import { router } from '@inertiajs/vue3';
import { clientService } from '@/services/clientService';
import type { Cliente, ClienteFormData } from '@/types/client';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { useToast } from '@/components/ui/toast/use-toast';
import { Loader2, Save, ArrowLeft } from 'lucide-vue-next';

export default defineComponent({
  name: 'ClientForm',
  
  props: {
    isEdit: {
      type: Boolean,
      default: false
    },
    clientId: {
      type: [String, Number],
      default: null
    }
  },

  setup() {
    return {
      router
    };
  },

  data() {
    return {
      loading: false,
      saving: false,
      error: null as string | null,
      form: {
        nombre: '',
        apellido: '',
        correo_electronico: '',
        telefono: '',
        direccion: '',
        fecha_nacimiento: '',
        ci: ''
      } as ClienteFormData
    };
  },

  computed: {
    isEditing(): boolean {
      return this.isEdit || !!this.clientId;
    },
    
    clientIdToEdit(): number | null {
      return this.clientId ? Number(this.clientId) : null;
    }
  },

  methods: {
    goBack() {
      window.history.back();
    },
    async loadClient(id: number) {
      try {
        this.loading = true;
        const client = await clientService.getClient(id);
        this.form = {
          nombre: client.nombre,
          apellido: client.apellido || '',
          correo_electronico: client.correo_electronico || '',
          telefono: client.telefono || '',
          direccion: client.direccion || '',
          fecha_nacimiento: client.fecha_nacimiento?.split('T')[0] || '',
          ci: client.ci || ''
        };
      } catch (err) {
        this.error = 'Error al cargar los datos del cliente';
        console.error('Error loading client:', err);
      } finally {
        this.loading = false;
      }
    },

    async saveClient() {
      try {
        this.saving = true;
        this.error = null;
        const { toast } = useToast();

        if (this.isEditing && this.clientIdToEdit) {
          await clientService.updateClient(this.clientIdToEdit, this.form);
          toast({
            title: 'Cliente actualizado',
            description: 'Los datos del cliente se han actualizado correctamente.',
            variant: 'default'
          });
        } else {
          await clientService.createClient(this.form);
          toast({
            title: 'Cliente creado',
            description: 'El cliente se ha creado correctamente.',
            variant: 'default'
          });
        }

        router.visit(route('clientes.index'));
      } catch (err: any) {
        console.error('Error saving client:', err);
        
        let errorMessage = 'Error al guardar los datos del cliente';
        if (err.response?.data?.errors) {
          const errors = Object.values(err.response.data.errors).flat();
          errorMessage = errors.join('\n');
        } else if (err.response?.data?.message) {
          errorMessage = err.response.data.message;
        }
        
        this.error = errorMessage;
        const { toast } = useToast();
        toast({
          title: 'Error',
          description: errorMessage,
          variant: 'destructive'
        });
      } finally {
        this.saving = false;
      }
    }
  },

  mounted() {
    if (this.isEditing && this.clientIdToEdit) {
      this.loadClient(this.clientIdToEdit);
    }
  }
});
</script>

<template>
  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-bold">
          {{ isEditing ? 'Editar Cliente' : 'Nuevo Cliente' }}
        </h1>
        <p class="text-muted-foreground">
          {{ isEditing ? 'Actualice los datos del cliente' : 'Complete el formulario para registrar un nuevo cliente' }}
        </p>
      </div>
      <Button variant="outline" @click="goBack">
        <ArrowLeft class="w-4 h-4 mr-2" />
        Volver
      </Button>
    </div>

    <Card>
      <form @submit.prevent="saveClient">
        <CardHeader>
          <CardTitle>Datos del Cliente</CardTitle>
          <CardDescription>
            Complete la información del cliente. Los campos marcados con * son obligatorios.
          </CardDescription>
        </CardHeader>
        
        <CardContent class="space-y-4">
          <div v-if="error" class="p-4 text-sm text-destructive bg-destructive/10 rounded-md">
            {{ error }}
          </div>
          
          <div v-if="loading" class="flex justify-center p-8">
            <Loader2 class="h-8 w-8 animate-spin" />
          </div>
          
          <div v-else class="grid gap-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="space-y-2">
                <Label for="nombre">Nombre completo *</Label>
                <Input
                  id="nombre"
                  v-model="form.nombre"
                  placeholder="Ej: Juan Pérez"
                  required
                />
              </div>
              
              <div class="space-y-2">
                <Label for="correo_electronico">Correo electrónico</Label>
                <Input
                  id="correo_electronico"
                  v-model="form.correo_electronico"
                  type="email"
                  placeholder="Ej: juan@example.com"
                />
              </div>
              
              <div class="space-y-2">
                <Label for="telefono">Teléfono</Label>
                <Input
                  id="telefono"
                  v-model="form.telefono"
                  placeholder="Ej: +591 71234567"
                />
              </div>
              
              <div class="space-y-2">
                <Label for="password_hash">
                  {{ isEditing ? 'Nueva contraseña' : 'Contraseña' }}
                  <span v-if="!isEditing"> *</span>
                </Label>
                <Input
                  id="password_hash"
                  v-model="form.password_hash"
                  type="password"
                  :required="!isEditing"
                  :placeholder="isEditing ? 'Dejar en blanco para no cambiar' : 'Mínimo 6 caracteres'"
                />
              </div>
              
              <div class="space-y-2 md:col-span-2">
                <Label for="direccion">Dirección</Label>
                <Input
                  id="direccion"
                  v-model="form.direccion"
                  placeholder="Ej: Av. Principal #123, Zona Central"
                />
              </div>
            </div>
          </div>
        </CardContent>
        
        <CardFooter class="flex justify-between">
          <Button type="button" variant="outline" @click="goBack">
            Cancelar
          </Button>
          <Button type="submit" :disabled="saving">
            <Loader2 v-if="saving" class="mr-2 h-4 w-4 animate-spin" />
            <Save v-else class="mr-2 h-4 w-4" />
            {{ saving ? 'Guardando...' : 'Guardar' }}
          </Button>
        </CardFooter>
      </form>
    </Card>
  </div>
</template>
