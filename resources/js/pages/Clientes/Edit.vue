<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import ClienteForm from './Partials/ClienteForm.vue';
import { router } from '@inertiajs/vue3';
import { clientService } from '@/services/clientService';
import type { ClienteFormData } from '@/types/client';

const handleSubmit = async (formData: ClienteFormData) => {
  try {
    // Aquí deberías obtener el ID del cliente desde las props o route
    const clienteId = route().params.id;
    await clientService.updateClient(Number(clienteId), formData);
    router.visit(route('clientes.show', clienteId));
  } catch (error) {
    console.error('Error updating client:', error);
    // Aquí podrías agregar manejo de errores para el usuario
  }
};
</script>

<template>
  <AppLayout>
    <ClienteForm :is-edit="true" @submit="handleSubmit" />
  </AppLayout>
</template>