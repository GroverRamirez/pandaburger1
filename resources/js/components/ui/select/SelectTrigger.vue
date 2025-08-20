<template>
  <select
    :value="modelValue"
    @change="handleChange"
    :class="[
      'flex h-10 w-full items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50',
      className
    ]"
  >
    <option v-if="placeholder" value="" disabled>{{ placeholder }}</option>
    <slot />
  </select>
</template>

<script setup lang="ts">
interface Props {
  modelValue?: string;
  placeholder?: string;
  className?: string;
}

const props = withDefaults(defineProps<Props>(), {
  placeholder: 'Seleccionar...',
  className: ''
});

const emit = defineEmits<{
  (e: 'update:modelValue', value: string): void;
}>();

const handleChange = (event: Event) => {
  const target = event.target as HTMLSelectElement;
  emit('update:modelValue', target.value);
};
</script>
