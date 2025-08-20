<script setup lang="ts">
import type { HTMLAttributes } from 'vue'
import { cn } from '@/lib/utils'

const props = withDefaults(defineProps<{
  modelValue?: string | number | null
  type?: string
  class?: HTMLAttributes['class']
  placeholder?: string
}>(), {
  type: 'text',
})

const emit = defineEmits<{
  (e: 'update:modelValue', value: string | number | null): void
}>()

const onInput = (e: Event) => {
  const target = e.target as HTMLInputElement
  emit('update:modelValue', props.type === 'number' ? Number(target.value) : target.value)
}
</script>

<template>
  <input
    :type="type"
    :value="modelValue as any"
    @input="onInput"
    :placeholder="placeholder"
    :class="cn('flex h-9 w-full rounded-md border border-input bg-background px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-[3px] focus-visible:ring-ring/50 disabled:cursor-not-allowed disabled:opacity-50', props.class)"
  />
</template>

