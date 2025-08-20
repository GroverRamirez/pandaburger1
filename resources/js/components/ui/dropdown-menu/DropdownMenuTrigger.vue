<script setup lang="ts">
import { ref } from 'vue';

interface Props {
  className?: string;
}

const props = withDefaults(defineProps<Props>(), {
  className: ''
});

const isOpen = ref(false);

const toggleDropdown = () => {
  isOpen.value = !isOpen.value;
};
</script>

<template>
  <button
    type="button"
    :class="[
      'inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50',
      className
    ]"
    @click="toggleDropdown"
  >
    <slot />
  </button>
</template>
