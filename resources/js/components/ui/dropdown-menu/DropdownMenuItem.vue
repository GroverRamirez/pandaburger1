<template>
  <div
    :class="[
      'relative flex cursor-pointer items-center gap-2 rounded-sm px-2 py-1.5 text-sm outline-none select-none transition-colors hover:bg-accent hover:text-accent-foreground focus:bg-accent focus:text-accent-foreground data-[disabled]:pointer-events-none data-[disabled]:opacity-50',
      inset ? 'pl-8' : '',
      variant === 'destructive' ? 'text-destructive focus:bg-destructive/10 focus:text-destructive' : '',
      className
    ]"
    @click="handleClick"
  >
    <slot />
  </div>
</template>

<script setup lang="ts">
interface Props {
  className?: string;
  inset?: boolean;
  variant?: 'default' | 'destructive';
  disabled?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
  className: '',
  inset: false,
  variant: 'default',
  disabled: false
});

const emit = defineEmits<{
  (e: 'click'): void;
}>();

const handleClick = () => {
  if (!props.disabled) {
    emit('click');
  }
};
</script>
