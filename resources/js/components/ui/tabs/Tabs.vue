<template>
  <div>
    <slot />
  </div>
</template>

<script setup lang="ts">
import { provide, ref } from 'vue';

const activeTab = ref<string>('');

provide('tabs', {
  activeTab,
  setActiveTab: (value: string) => {
    activeTab.value = value;
  }
});
</script>
