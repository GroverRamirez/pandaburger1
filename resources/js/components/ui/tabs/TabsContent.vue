<template>
  <div
    v-show="isActive"
    :class="[
      'mt-2 ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2'
    ]"
  >
    <slot />
  </div>
</template>

<script setup lang="ts">
import { inject, computed } from 'vue';

interface TabsContext {
  activeTab: { value: string };
}

const props = defineProps<{
  value: string;
}>();

const tabs = inject<TabsContext>('tabs');

const isActive = computed(() => tabs?.activeTab.value === props.value);
</script>
