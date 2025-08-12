<script setup lang="ts">
import { computed } from 'vue';

interface Props {
    modelValue?: string;
    type?: string;
    placeholder?: string;
    required?: boolean;
    autofocus?: boolean;
    tabindex?: number;
    autocomplete?: string;
    id?: string;
    error?: string;
}

const props = withDefaults(defineProps<Props>(), {
    type: 'text',
    modelValue: '',
    placeholder: '',
    required: false,
    autofocus: false,
    tabindex: 0,
    autocomplete: 'off',
    id: '',
    error: '',
});

const emit = defineEmits<{
    'update:modelValue': [value: string];
}>();

const hasError = computed(() => !!props.error);
const inputClasses = computed(() => [
    'w-full px-3 sm:px-4 py-2 sm:py-3 pl-10 sm:pl-12 bg-white/10 border rounded-lg sm:rounded-xl text-white placeholder:text-slate-400 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-orange-400/50 focus:border-orange-400 text-sm sm:text-base',
    hasError.value 
        ? 'border-red-500/50 focus:border-red-400 focus:ring-red-400/50' 
        : 'border-orange-500/30 focus:border-orange-400'
]);

const updateValue = (event: Event) => {
    const target = event.target as HTMLInputElement;
    emit('update:modelValue', target.value);
};
</script>

<template>
    <div class="relative">
        <slot name="icon" />
        <input
            :id="id"
            :type="type"
            :placeholder="placeholder"
            :required="required"
            :autofocus="autofocus"
            :tabindex="tabindex"
            :autocomplete="autocomplete"
            :value="modelValue"
            @input="updateValue"
            :class="inputClasses"
        />
        <div 
            v-if="hasError" 
            class="absolute -bottom-5 sm:-bottom-6 left-0 text-xs text-red-400 font-medium"
        >
            {{ error }}
        </div>
    </div>
</template>
