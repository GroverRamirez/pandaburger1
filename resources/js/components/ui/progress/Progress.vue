<template>
  <div class="w-full overflow-hidden rounded-full bg-secondary">
    <div
      class="h-full w-full flex-1 bg-primary transition-all"
      :style="{ width: `${value}%` }"
    />
  </div>
</template>

<script setup lang="ts">
defineProps<{
  value: number;
}>();
</script>
