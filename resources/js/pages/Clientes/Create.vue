<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import ClienteForm from './Partials/ClienteForm.vue';
import { router } from '@inertiajs/vue3';
import { clientService } from '@/services/clientService';

const handleSubmit = async (formData: any) => {
  try {
    await clientService.createClient(formData);
    router.visit(route('clientes.index'));
  } catch (error) {
    console.error('Error creating client:', error);
    // Here you could add user-facing error handling
  }
};
</script>

<template>
  <AppLayout>
    <ClienteForm @submit="handleSubmit" />
  </AppLayout>
</template>